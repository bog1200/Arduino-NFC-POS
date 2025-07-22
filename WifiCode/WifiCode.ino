#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <WiFiClient.h>

unsigned long lastMillis = 0;

ESP8266WebServer server(80);
String header;
void printMult(String str, int n)
{
  for (int i = 0; i < n; i++)
  {
    Serial.println(str);
    delay(50);
  }
}
void printDelay(String str)
{
  Serial.println(str);
  delay(50);
}

void setup()
{
  Serial.begin(9600);
  Serial.println();
  // WiFi.config(IPAddress(192, 168, 0, 2), IPAddress(192, 168, 0, 1), IPAddress(255, 255, 255, 0));
  WiFi.begin("net1", "password");

  Serial.println("Connecting...");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.println(".");
  }

  printDelay("Connected, IP address: ");
  printDelay(WiFi.localIP().toString());
  printDelay(WiFi.macAddress());
  printDelay(WiFi.hostname());
  printDelay(WiFi.SSID());
  printDelay(String(WiFi.RSSI()));
  printDelay(WiFi.BSSIDstr());
  printDelay(WiFi.gatewayIP().toString());
  printMult("ready", 1);
  server.begin();
  server.on("/pay", handlePay);
}

void loop()
{
  server.handleClient();
}

void handlePay()
{
  String price = server.arg("price");
  printDelay("START");
  printDelay(price);
  unsigned long startMillis = millis();
  String nfc_id = "";
  do
  {
    if (Serial.available() > 0)
    {
      nfc_id = Serial.readStringUntil('\n');
      Serial.println("NFC_ID000:");
      Serial.println(nfc_id);
    }
  } while (nfc_id.indexOf("NFC_ID") < 0 && millis() - startMillis < 15000);
  if (millis() - startMillis >= 15000)
  {
    server.send(400, "text/plain", "TIMEOUT");
    return;
  }
  while (!Serial.available())
  {
    delay(50);
  }

  nfc_id = Serial.readStringUntil('\n');
  Serial.println("NFC_ID22:");
  Serial.println(nfc_id);
  server.send(200, "text/plain", nfc_id);
}

// void loop()
// {
//   WiFiClient client = server.available(); // Listen for incoming clients
//   if (client)
//   {
//     Serial.println("New Client.");
//     String currentLine = ""; // make a String to hold incoming data from the client
//     while (client.connected())
//     {
//       if (client.available())
//       {
//         char c = client.read(); // read a byte, then
//         Serial.write(c);        // print it out the serial monitor
//         header += c;
//         if (c == '\n')
//         {
//           if (currentLine.length() == 0)
//           {
//             // if url is /api/pay
//             if (header.indexOf("/api/pay") >= 0)
//             {
//               Serial.println("Pay");
//               Serial.println(header);

//               client.println("HTTP/1.1 200 OK");
//               client.println("Content-type:text/html");
//               client.println("Connection: close");
//               client.println();
//               break;
//             }

//             // client.println("HTTP/1.1 200 OK");
//             // client.println("Content-type:text/html");
//             // client.println("Connection: close");
//             // client.println();
//             // client.println("<!DOCTYPE html><html>");
//             // client.println("<head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">");
//             // client.println("<link rel=\"icon\" href=\"data:,\">");
//             // client.println("<style>html { font-family: Helvetica; display: inline-block; margin: 0px auto; text-align: center;}");
//             // client.println(".button { background-color: #195B6A; border: none; color: white; padding: 16px 40px;");
//             // client.println("text-decoration: none; font-size: 30px; margin: 2px; cursor: pointer;}");
//             // client.println(".button2 {background-color: #77878A;}</style></head>");
//             // client.println("<body><h1>ESP8266 Web Server</h1>");
//             // client.println("<p>Signal: ");
//             // client.println(WiFi.RSSI());
//             // client.println("</p>");
//             // client.println("</body></html>");
//             // client.println();
//             // break;
//           }
//           else
//           {
//             currentLine = "";
//           }
//         }
//         else if (c != '\r')
//         {
//           currentLine += c;
//         }
//       }
//     }
//   }
// }
