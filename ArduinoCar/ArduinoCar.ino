#include <WiFiS3.h>
#include <UnoR4WiFi_WebServer.h>
#include "secrets.h"

// Prototypes
void connectWifi();
void webSocketEvent(WebSocket &, const WebSocket::DataType, const char *, uint16_t);
void moveForward(int);
void handleCommand(WebSocket &, const String &);

constexpr uint16_t WS_PORT = 81;
WebSocketServer wss{WS_PORT};

// --- Motor Pins ---
#define EN1 5
#define IN1 7
#define IN2 8
#define EN2 6
#define IN3 9
#define IN4 10

// --- Fail-Safe & Debug Variables ---
unsigned long lastCommandTime = 0;
const unsigned long TIMEOUT_MS = 500;
bool isMoving = false;
bool wasConnected = false; // Keeps track of connection state to stop spam

void setup() {
  Serial.begin(9600);
  connectWifi();

  wss.onConnection([](WebSocket &ws) {
    Serial.println("New WebSocket connection");

    ws.onMessage(webSocketEvent);

    const char welcome[] = "Connected to Arduino!";
    ws.send(WebSocket::DataType::TEXT, welcome, strlen(welcome));
  });

  wss.begin();

  pinMode(EN1, OUTPUT); pinMode(IN1, OUTPUT); pinMode(IN2, OUTPUT);
  pinMode(EN2, OUTPUT); pinMode(IN3, OUTPUT); pinMode(IN4, OUTPUT);
  stopMotors();
}

void loop() {
  wss.listen();

  if (isMoving && (millis() - lastCommandTime > TIMEOUT_MS)) {
    stopMotors();
    isMoving = false;
    Serial.println("⚠️ Fail-safe triggered: stopping motors.");
  }

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

// --- WebSocket Event Handler ---
void webSocketEvent(WebSocket &ws, const WebSocket::DataType dataType, const char *message, uint16_t length) {
  switch (dataType) {
    case WebSocket::DataType::TEXT:{
      // Copy using the length, since the buffer may not be null-terminated
      String msg;
      msg.reserve(length);
      for (uint16_t i = 0; i < length; i++) msg += message[i];
      msg.trim();

      Serial.print("Text: ");
      Serial.println(msg);

      handleCommand(ws, msg);
      break;
    }
    case WebSocket::DataType::BINARY: {
      Serial.print("Binary message, ");
      Serial.print(length);
      Serial.println(" bytes (ignored)");
      break;
    }
    default:
      Serial.println("Unknown WebSocket data type");
      break;
  }
}

// --- Command parsing (adjust the strings to whatever your client sends) ---
void handleCommand(WebSocket &ws, const String &cmd) {
  const int SPEED = 255; // 0-255
  switch (cmd[0]) {
    case 'F':
      moveForward(SPEED);
      break;
    case 'B':
      moveBackward(SPEED);
      break;
    case 'L':
      turnLeft(SPEED);
      break;
    case 'R':
      turnRight(SPEED);
      break;
    case 'S':
      stopMotors();
      isMoving = false;
      return;
    default:
      Serial.println("Unknown command");
      return;
  }

  // Refresh the fail-safe timer so loop() doesn't stop the motors
  lastCommandTime = millis();
  isMoving = true;
}

// --- Motor Functions ---
void moveForward(int speed) {
  digitalWrite(IN1, HIGH); digitalWrite(IN2, LOW); analogWrite(EN1, speed);
  digitalWrite(IN3, LOW);  digitalWrite(IN4, HIGH); analogWrite(EN2, speed);
}

void moveBackward(int speed) {
  digitalWrite(IN1, LOW);  digitalWrite(IN2, HIGH); analogWrite(EN1, speed);
  digitalWrite(IN3, HIGH); digitalWrite(IN4, LOW); analogWrite(EN2, speed);
}

void turnLeft(int speed) {
  digitalWrite(IN1, HIGH); digitalWrite(IN2, LOW); analogWrite(EN1, speed);
  digitalWrite(IN3, HIGH); digitalWrite(IN4, LOW); analogWrite(EN2, speed);
}

void turnRight(int speed) {
  digitalWrite(IN1, LOW);  digitalWrite(IN2, HIGH); analogWrite(EN1, speed);
  digitalWrite(IN3, LOW);  digitalWrite(IN4, HIGH); analogWrite(EN2, speed);
}

void stopMotors() {
  digitalWrite(IN1, LOW); digitalWrite(IN2, LOW);
  digitalWrite(IN3, LOW); digitalWrite(IN4, LOW);
  analogWrite(EN1, 0); analogWrite(EN2, 0);
}