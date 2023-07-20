#include <WiFi.h>
#include <HTTPClient.h>

// const char* ssid = "********";
// const char* password = "********";
const char* ssid = "********";
const char* password = "********";
const char* serverUrl = "http://********";

const int touchPin = 13; 
const int ledPin = 2;

// variable for storing the touch pin value 
int touchValue;

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  pinMode (ledPin, OUTPUT);
}

void loop() {
  touchValue = touchRead(touchPin);
  // Your data to send
  String value = String(touchValue);
  // Create an HTTPClient object
  HTTPClient http;

  // Set the target URL
  http.begin(serverUrl);

  // Add headers if needed
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Make the POST request
  Serial.println(value);
  String httpRequestData = "value=" + value;
  int httpResponseCode = http.POST(httpRequestData);
  Serial.println(httpResponseCode);
  // Check the response
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Response: " + response);
  } else {
    Serial.println("Error on HTTP request");
  }

  // Close the connection
  http.end();

  // Wait before sending the next request
  delay(500);
}
