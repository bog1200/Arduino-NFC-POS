#define BUZZER 2
#define BUTTON_10 14 // A0
#define BUTTON_5 15  // A1
#define BUTTON_1 16  // A2
#define BUTTON_0 17  // A3
#include <Wire.h>
#include <PN532_I2C.h>
#include <PN532.h>
#include <NfcAdapter.h>
#include <LiquidCrystal.h>
unsigned long lcd_millis = 0;
void printLCD(const char *line1, const char *line2 = nullptr);
void printLCD(int column, int row, char character);
/*

  The circuit:
  SD card attached to SPI bus as follows:
 ** MOSI - pin 11
 ** MISO - pin 12
 ** CLK - pin 13
 ** CS - pin 10

*/
#include <SPI.h>
#include <SD.h>
unsigned int sum = 0;

PN532_I2C pn532_i2c(Wire);
LiquidCrystal lcd(8, 7, 6, 5, 4, 3);
NfcAdapter nfc = NfcAdapter(pn532_i2c);
String tagId = "None";
byte nuidPICC[4];
File root;

uint8_t Lock[8] = {
    0b01110,
    0b10001,
    0b10001,
    0b11111,
    0b11011,
    0b11011,
    0b11111,
    0b00000};

uint8_t Unlock[8] = {
    0b01110,
    0b10001,
    0b10000,
    0b11111,
    0b11011,
    0b11011,
    0b11111,
    0b00000};

uint8_t Wifi[8] = {
    0b00000,
    0b00000,
    0b01110,
    0b10001,
    0b00000,
    0b01110,
    0b10001,
    0b00100};

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
    Serial.begin(115200);
    initLCD();
    initSD();
    initNFC();
    pinMode(BUTTON_10, INPUT_PULLUP);
    pinMode(BUTTON_5, INPUT_PULLUP);
    pinMode(BUTTON_1, INPUT_PULLUP);
    pinMode(BUTTON_0, INPUT_PULLUP);
    pinMode(BUZZER, OUTPUT);
}

void loop()
{
    if (digitalRead(BUTTON_10) == LOW)
    {
        addSum(10);
        buzz(100, 1000);
    }
    if (digitalRead(BUTTON_5) == LOW)
    {
        addSum(5);
        buzz(100, 1000);
    }
    if (digitalRead(BUTTON_1) == LOW)
    {
        addSum(1);
        buzz(100, 1000);
    }
    if (digitalRead(BUTTON_0) == LOW)
    {
        buzz(500, 500);
        readNFC();
    }
    displaySum();
}
void initLCD()
{
    lcd.begin(16, 2);
    printLCD("Starting...");
    lcd.createChar(7, Lock);
    lcd.createChar(6, Unlock);
    lcd.createChar(5, Signal);
}

void initSD()
{
    printLCD("SD CARD", "Loading...");
    if (!SD.begin(SS))
    {
        printLCD("SD CARD", "NOT FOUND!");
        return;
    }
    printLCD("SD CARD", "Loaded");
}

void initNFC()
{
    printLCD("NFC", "Loading...");
    nfc.begin(true);
    printLCD("NFC", "Loaded");
}

void displaySum()
{
    if (millis() - lcd_millis > 1000)
    {
        lcd.clear();
        if (sum == 0)
        {
            printLCD("Please enter", "ammount");
        }
        else
        {
            printLCD("Charge sum:");
            lcd.setCursor(0, 1);
            lcd.print(sum);
        }

        lcd_millis = millis();
    }
}
void addSum(unsigned int toAdd)
{
    if (sum + toAdd <= 99)
        sum += toAdd;
    else
        sum = 99;
    Serial.println(sum);
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

void readNFC()
{
    // lcd.print("Starting reader...");
    // delay(2000);
    printLCD("Awaiting card...");
    printLCD(15, 1, uint8_t(5));
    if (nfc.tagPresent(3000))
    {
        buzz(2000, 1000);
        NfcTag tag = nfc.read();
        tagId = tag.getUidString();
        printLCD("Card read", tagId.c_str());
        printLCD(14, 1, uint8_t(5));
        printLCD(15, 1, uint8_t(6));
        sum = 0;
        delay(3000);
    }
}