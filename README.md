# Formula
Andreas, Mark, Faas og Daphne

![Whiteboard](https://raw.githubusercontent.com/Mercantec-GHC/NL-Selfdriving-Car/refs/heads/main/whiteboard.svg)

---

## 📋 MoSCoW Prioritization

### 🟥 MUST HAVE
**Hardware & Assembly**
* 4WD Car Kit Assembly: Chassis built, 4x TT motors mounted, wheels attached.
* Motor Wiring: Left motors wired in parallel to A+/A-; Right motors wired in parallel to B+/B- on the Deek Shield screw terminals.
* Power System: 8xAA battery pack plugged directly into the Arduino Uno R4.
* Motor Driver: Deek Robot Shield V1 (L298P) seated on the Uno R4.

**Software & Control**
* Wi-Fi Connection: Arduino Uno R4 connects to the designated network.
* WebSocket Communication: Two-way WebSocket established between the website and Arduino.
* Website UI: Mockup created, and a mobile-friendly webpage built with large Forward, Backward, Left, Right, and Stop buttons.
* Basic Movement: Car responds to commands (using IN1-IN4 for direction, EN1-EN2 for PWM speed).
* Fail-Safe (Stall Timeout): If no command is received for 500ms, motors stop.
* Soft-Start Ramping: Code ramps PWM up/down over ~300ms to prevent brownouts and protect the gears.
* PWM Capping: Maximum speed capped in code (e.g., max 180/255) to protect the 6V motors from the 9V battery pack.

### 🟨 SHOULD HAVE
**Hardware**
* Power Stability: Insert the 1000µF electrolytic capacitor into the unused VIN and GND screw terminals on the shield to absorb power spikes.
* Status LED: A physical LED on a breadboard that lights up when the car is connected to Wi-Fi.

**Software**
* Reconnection Logic: If the Wi-Fi drops, the Arduino attempts to reconnect automatically without needing a physical reset. (feedback on status LED).
* Web UI Feedback: The website shows a visual indicator of whether the car is currently "Connected" or "Disconnected".
* Speed Control: A slider or multiple speed settings on the website.

### 🟩 COULD HAVE
**Hardware**
* Motor Noise Suppression: Solder the 100nF ceramic capacitors across the terminals of the TT motors (Optional, only if glitches occur).

**Sensors & Features (Using jumper wires/breadboard)**
* Automatic Headlights: Use the GL5528 LDR and 5mm White LEDs.
* Smart Brake Lights & 3rd Brake Light: Use the ADXL345 Accelerometer to detect deceleration and turn on rear red LEDs.
* Horn: Use the Active Buzzer triggered by a button on the website.
* Obstacle Avoidance: Use the HC-SR04 Ultrasonic sensor to auto-stop if a wall is detected within 15cm.
* Crash Detection: Use the ADXL345 Accelerometer to detect sudden jolts and send a "Crash!" alert to the website.
* Precision Turns & Straight Driving: Use the ADXL345 to make the car turn exactly 90 degrees or auto-correct steering.
* On-Car Telemetry: Use the LCD 20x4 (I2C) to display connection status, speed, or distance.
* Cabin/Engine Temp: Use the LM35 Thermometer to display temperature.
* Police Sirens: Use the 5mm LEDs to simulate police sirens once a button is pressed on the website.
* WS2812B Effects: Use the digital LED strip for underglow or turn signals.

**Aesthetics**
* 3D Printed Shell/Chassis: A custom Formula 1 style body, spoiler, or mounting brackets.

### 🟦 WON'T HAVE
* Front-Wheel Steering Servo or Panning Servo: Committed to tank steering (skid steer). The SG90 will not be used.
* Line Tracking: Using the IR sensors to follow a track.
* Custom Motor Encoders / PID: Won't attempt precise odometry or advanced closed-loop speed control.
* Database / User Accounts: No saving of driving history.
* SignalR: Plain WebSockets are perfectly fine.

---

## 🗓️ Weekly Plan & Definitions of Done

### Week 2: The "First Drive"
**Milestone Goal:** Prove that the hardware, WebSockets, and website can talk to each other and move the car.

**✅ Definition of Done:**
- [ ] **Hardware:** 4WD Car Kit is fully assembled, motors mounted, and wheels attached.
- [ ] **Hardware:** Left motors are wired in parallel to `A+/A-` and right motors to `B+/B-` on the Deek Shield.
- [ ] **Hardware:** 6xAA battery pack is plugged directly into the Arduino Uno R4, and the board powers on.
- [ ] **Hardware:** Deek Robot Shield V1 is securely seated on the Uno R4.
- [ ] **Software:** Arduino Uno R4 connects to the designated Wi-Fi network.
- [ ] **Software:** A two-way WebSocket connection is established between the website and the Arduino.
- [ ] **Software:** The mobile-friendly webpage is live with large Forward, Backward, Left, Right, and Stop buttons (based on an agreed mockup).
- [ ] **Software:** Basic movement works. Pressing a button sends the correct signals to `IN1-IN4` and `EN1-EN2` to move the car via tank steering.
- [ ] **Safety:** Fail-Safe (Stall Timeout) is active. If no command is received for 500ms, the motors stop.
- [ ] **Safety:** Soft-Start Ramping is implemented (PWM ramps over ~300ms).
- [ ] **Safety:** PWM Capping is implemented (Max speed capped at ~180/255).
- [ ] **Process:** Code is pushed to GitHub. `README.md` has basic run instructions.

### Week 3: "Stability & Control"
**Milestone Goal:** Make the car reliable, add physical fallbacks, and ensure the user has good feedback.

**✅ Definition of Done:**
- [ ] **Hardware (Optional):** If necessary, the 1000µF electrolytic capacitor is inserted into the unused `VIN` and `GND` screw terminals on the shield.
- [ ] **Hardware:** A physical Status LED is set up on the breadboard.
- [ ] **Software:** The Status LED provides feedback on the Reconnection Logic (e.g., solid when connected, blinking when trying to reconnect, red when disconnected).
- [ ] **Software:** Reconnection Logic is active. The Arduino automatically attempts to reconnect to Wi-Fi without a physical reset.
- [ ] **Software:** The Web UI shows a visual indicator of whether the car is "Connected" or "Disconnected".
- [ ] **Software:** Speed Control is added to the website (a slider or multiple speed settings).
- [ ] **Testing:** The team completes a Stress Test (driving into walls, rapid button mashing, turning the router off/on) to verify the capacitor and reconnection logic work.

### Week 4: "Polish & Demo"
**Milestone Goal:** Add the fun features, finish the aesthetics, and prepare for the final presentation. *(Note: Feature Freeze for all Must/Should Haves is Tuesday. Only Could-Haves and bug fixes after that).*

**✅ Definition of Done:**
- [ ] **Aesthetics:** The 3D Printed Shell/Chassis (F1-style body, spoiler, or mounting brackets), if time permits, is designed, printed, and attached.
- [ ] **Software/Hardware:** At least two Could-Have features are fully integrated and working. 
- [ ] **Hardware (Optional):** If necessary, the 100nF ceramic capacitors are soldered to the TT motors to suppress noise.
- [ ] **Documentation:** The `README.md` is fully complete with a wiring diagram, project photos, and team roles.
- [ ] **Presentation:** The team has rehearsed a 5-minute live demo.
- [ ] **Presentation:** A fallback plan (e.g., a recorded video of the car) is ready in case the school Wi-Fi blocks the WebSocket connection during the presentation.
- [ ] **Process:** No critical bugs remain in the core Must-Have features.
