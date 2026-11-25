#include <WiFiS3.h>
#include <UnoR4WiFi_WebServer.h>
#include "secrets.h"

// Prototypes
void connectWifi();

constexpr uint16_t WS_PORT = 81;
WebSocketServer wss{WS_PORT};


void setup() {
  Serial.begin(9600);
  connectWifi();

  wss.onConnection([](WebSocket &ws) {
    Serial.println("New WebSocket connection");

    ws.onMessage([](WebSocket &ws, const WebSocket::DataType dataType,
                      const char *message, uint16_t length) {
      // Copy into a String using the length, in case it isn't null-terminated
/*       String msg;
      msg.reserve(length);
      for (uint16_t i = 0; i < length; i++) msg += message[i]; */

      Serial.print("Text: ");
      Serial.println(message);
    });

    const char welcome[] = "Connected to Arduino!";
    ws.send(WebSocket::DataType::TEXT, welcome, strlen(welcome));
  });

  wss.begin();
}

void loop() {
  wss.listen();
  delay(10);
}

void connectWifi() {
  Serial.print("Connecting to ");
  Serial.println(WIFI_SSID);

  while (WiFi.begin(WIFI_SSID, WIFI_PASS) != WL_CONNECTED) {
    Serial.println("Retrying...");
    delay(5000);
  }

  // Since we can connect to the WIFI before we get assigned an IP, we wait until we
  while (WiFi.localIP() == IPAddress(0, 0, 0, 0)) {
    Serial.println("Waiting for DHCP response");
    delay(500);
  }

  Serial.println("Connected!");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}