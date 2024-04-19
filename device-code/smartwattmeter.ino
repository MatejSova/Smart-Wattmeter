#include <driver/adc.h>
#include "EmonLib.h"
#include <Arduino.h>
#include <ZMPT101B.h>
#include <WiFi.h>
#include <WiFiMulti.h>
#include <HTTPClient.h>
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <WiFiClientSecure.h>
#include <WiFiManager.h>

#define BUTTON_PIN 13
#define ADC_INPUT_VOLTAGE 34
#define ADC_INPUT_CURRENT_1 35
#define ADC_INPUT_CURRENT_2 32
#define ADC_INPUT_CURRENT_3 33
#define ADC_BITS    12
#define SENSITIVITY 755.0f

EnergyMonitor emon1;
EnergyMonitor emon2;
EnergyMonitor emon3;
WiFiMulti wifiMulti;
HTTPClient http;
WiFiManager wm;
ZMPT101B voltageSensor(ADC_INPUT_VOLTAGE, 50.0);
LiquidCrystal_I2C lcd(0x27,20,4); 
short measurements_V[30];
short measurements_A1[30];
short measurements_A2[30];
short measurements_A3[30];
short measureIndex = 0;
unsigned long lastMeasurement = 0;
unsigned long timeFinishedSetup = 0;
String apiKeyValue = "tPmAT5Ab3j7F9";
int zariadenie = 1;

void setup() {
  Serial.begin(115200);
  pinMode(BUTTON_PIN, INPUT_PULLUP);
  lcd.init();                      // initialize the lcd 
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(2, 0);
  lcd.print("Pripoj sa k WIFI");
  lcd.setCursor(0,1);
  lcd.print("SSID: Smartwattmeter");
  lcd.setCursor(0,2);
  lcd.print("PASS: password");
  bool success = wm.autoConnect("Smartwattmeter", "password");
  if (!success) {
    Serial.println("Failed to connect");
  } else {
    Serial.println("Connected");
    Serial.println((String)wm.getWiFiSSID());
    String SSID = (String)wm.getWiFiSSID();
    const char* ssid = SSID.c_str();
    Serial.println((String)wm.getWiFiPass());
    String PASS = (String)wm.getWiFiPass();
    const char* pass = PASS.c_str();
    wifiMulti.addAP(ssid, pass);
  }

  http.setReuse(true);
  analogReadResolution(ADC_BITS);
  voltageSensor.setSensitivity(SENSITIVITY);
  emon1.current(ADC_INPUT_CURRENT_1, 30);
  emon2.current(ADC_INPUT_CURRENT_2, 30);
  emon3.current(ADC_INPUT_CURRENT_3, 30);
  timeFinishedSetup = millis();
  writeLcdScheme();
}

void checkButton(){
  if (digitalRead(BUTTON_PIN) == LOW) {
    delay(50);
    if (digitalRead(BUTTON_PIN) == LOW) {
      Serial.println("Button Pressed");
      delay(3000);  
      if (digitalRead(BUTTON_PIN) == LOW) {
        Serial.println("Button Held");
        Serial.println("Erasing Config, restarting");
        wm.resetSettings();
        ESP.restart();
      }
    }
  }
}

void loop() {
  unsigned long currentMillis = millis();
  if(currentMillis - lastMeasurement > 1000){
    checkButton();
    double amps_1 = emon1.calcIrms(1420); 
    double amps_2 = emon2.calcIrms(1420); 
    double amps_3 = emon3.calcIrms(1420); 
    double voltage = voltageSensor.getRmsVoltage();
    double watt_1 = amps_1 * voltage;
    double watt_2 = amps_2 * voltage;
    double watt_3 = amps_3 * voltage;
    writeEnergyToDisplay(watt_1, watt_2, watt_3, voltage, amps_1, amps_2, amps_3);
    lastMeasurement = millis();

    if(millis() - timeFinishedSetup < 10000){
      Serial.println("Startup mode   ");
    }else{
      measurements_V[measureIndex] = voltage;
      measurements_A1[measureIndex] = amps_1;
      measurements_A2[measureIndex] = amps_2;
      measurements_A3[measureIndex] = amps_3;
      measureIndex++;
    }
  }

  if (measureIndex == 30) {
    double pomV = 0;
    double pomA1 = 0;
    double pomA2 = 0;
    double pomA3 = 0;

    for (short i = 0; i <= 29; i++){
      pomV += measurements_V[i];
      pomA1 += measurements_A1[i];
      pomA2 += measurements_A2[i];
      pomA3 += measurements_A3[i];
    }

    double V = pomV/30;
    double A1 = pomA1/30;
    double A2 = pomA2/30;
    double A3 = pomA3/30;
    
    Serial.print(V);
    Serial.print(A1);
    Serial.print(A2);
    Serial.println(A3);
    sendToServer(zariadenie, V, A1, A2, A3);
    measureIndex = 0;
  }

}

void writeLcdScheme(){
  lcd.setCursor(0,0);
  lcd.print("U        V WIFI:  --");
  lcd.setCursor(0,1);
  lcd.print("I1       A P1      W");
  lcd.setCursor(0,2);
  lcd.print("I2       A P2      W");
  lcd.setCursor(0,3);
  lcd.print("I3       A P3      W");
}

void writeEnergyToDisplay(double watts_1, double watts_2, double watts_3, double voltage, double amps_1, double amps_2, double amps_3){
    lcd.setCursor(3,0);
    lcd.print(voltage);
    lcd.setCursor(4,1);
    lcd.print(amps_1);
    lcd.setCursor(4,2);
    lcd.print(amps_2);
    lcd.setCursor(4,3);
    lcd.print(amps_3);
    lcd.setCursor(14,1);
    lcd.print((int)watts_1);
    lcd.setCursor(14,2);
    lcd.print((int)watts_2);
    lcd.setCursor(14,3);
    lcd.print((int)watts_3);
  }

void sendToServer(int zariadenie, double voltage, double amps_1, double amps_2, double amps_3){
  if((wifiMulti.run() == WL_CONNECTED)) {
        lcd.setCursor(18,0);
        lcd.print("OK");
        http.begin("https://wattmeter.jecool.net/post_esp_data.php");
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
        String httpRequestData = "api_key=" + apiKeyValue + "&zariadenie=" + zariadenie
                          + "&napatie=" + voltage + "&prud_senzor_1=" + amps_1
                          + "&prud_senzor_2=" + amps_2 + "&prud_senzor_3=" + amps_3 + "";
        
        int httpCode = http.POST(httpRequestData);
        if(httpCode > 0) {
            Serial.printf("[HTTP] GET... code: %d\n", httpCode);
        } else {
            Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }
        http.end();
    }else
    {
      lcd.setCursor(18,0);
      lcd.print("--");
    }
    
} 