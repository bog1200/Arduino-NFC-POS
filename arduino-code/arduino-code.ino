#define BUZZER 2
#define WIFI_RX 10
#define WIFI_TX 9
#include <Wire.h>
#include <PN532_I2C.h>
#include <PN532.h>
#include <NfcAdapter.h>
#include <ArduinoJson.h>
#include <LiquidCrystal.h>
#include <AltSoftSerial.h>

#define DEBUG false // set to true for debug output, false for no debug output
#define DEBUG_SERIAL \
    if (DEBUG)       \
    Serial

unsigned long lcd_millis = 0;
void printLCD(const char *line1, const char *line2 = nullptr);
void printLCD(int column, int row, char character);

PN532_I2C pn532_i2c(Wire);
LiquidCrystal lcd(10, 7, 6, 5, 4, 3);
NfcAdapter nfc = NfcAdapter(pn532_i2c);
String tagId = "None";
byte nuidPICC[4];
AltSoftSerial wifiSerial; //(WIFI_RX, WIFI_TX);

uint8_t Signal[8] = {
    0b00000,
    0b00000,
    0b00001,
    0b00001,
    0b00101,
    0b00101,
    0b10101,
    0b00000};

void setup(void)
{
    DEBUG_SERIAL.begin(115200);
    wifiSerial.begin(9600);
    initLCD();
    initNFC();
    initWifi();
    pinMode(BUZZER, OUTPUT);
}

void loop()
{
    displaySum();
    if (wifiSerial.available())
    {
        String wifiString = wifiSerial.readStringUntil('\n');
        DEBUG_SERIAL.println("WL:" + wifiString);
        if (wifiString.indexOf("START") >= 0)
        {
            String sum = wifiSerial.readStringUntil('\n');
            displaySum();
            buzz(1000, 100);
            String nfc_id = readNFC(sum);
            DEBUG_SERIAL.println("NFC_ID:" + nfc_id);
            if (nfc_id != "")
            {
                printLCD("Sending", "Transaction");
                wifiSerial.println("NFC_ID");
                delay(50);
                wifiSerial.println(nfc_id.c_str());
                printLCD("Transaction", "Sent");
                delay(500);
            }
        }
    }
}
void initLCD()
{
    lcd.begin(16, 2);
    printLCD("Starting...");
    lcd.createChar(1, Signal);
}

void initNFC()
{
    printLCD("NFC", "Loading...");
    nfc.begin(true);
    printLCD("NFC", "Loaded");
}

void initWifi()
{
    String wifiString = "";
    printLCD("WIFI", "Starting...");
    while (wifiString.indexOf("ready") < 0)
    {
        if (wifiSerial.available())
        {
            wifiString = wifiSerial.readStringUntil('\n');
            DEBUG_SERIAL.println("W:" + wifiString);
        }
    }

    printLCD("WIFI", "Connected!");
}

void displaySum()
{
    if (millis() - lcd_millis > 2000)
    {
        lcd.clear();
        printLCD("Awaiting", "Transaction");
    }
    lcd_millis = millis();
}

void printLCD(const char *line1, const char *line2)
{
    if (strlen(line1) > 16 || strlen(line2) > 16)
        return;
    lcd.clear();
    lcd.print(line1);
    if (strlen(line2) > 0)
    {
        lcd.setCursor(0, 1);
        lcd.print(line2);
    }
}
void printLCD(int column, int row, char character)
{
    lcd.setCursor(column, row);
    lcd.print(character);
}

void buzz(int freq, int time)
{
    tone(BUZZER, freq); // Send sound signal...
    delay(time);        // ...for msec
    noTone(BUZZER);
}

String readNFC(String sum)
{
    // lcd.print("Starting reader...");
    // delay(2000);

    // Set line 1 to "Sum: " + sum
    delay(2000);
    DEBUG_SERIAL.println("Sum: " + sum);
    printLCD("Please tap card");
    lcd.setCursor(0, 1);
    lcd.print(sum);
    // lcd.setCursor(15, 1);
    // // print signal logo
    // lcd.write((uint8_t)1);

    if (nfc.tagPresent(2000))
    {
        buzz(2000, 1000);
        NfcTag tag = nfc.read();
        tagId = tag.getUidString();
        printLCD("In progress...", tagId.c_str());
        return tagId;
    }
}