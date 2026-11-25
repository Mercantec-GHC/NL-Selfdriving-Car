(() => {
  const statusFeed = document.getElementById('status-feed');
  const lastCommand = document.getElementById('last-cmd');
  const connection = document.getElementById('conn-badge');
  const movementLog = document.getElementById('movement-log');

  // Change this address to the IP address assigned to the Arduino.
  const arduinoIp = window.FORMULA_ARDUINO_IP || '192.168.0.100';
  const wsUrl = `ws://${arduinoIp}:81`;
  const storageKey = 'formula-movement-log';

  // The labels are used by the website; the values are sent to the Arduino.
  const commands = {
	FORWARD: 'F',
	LEFT: 'L',
	STOP: 'S',
	RIGHT: 'R',
	REVERSE: 'B',
  };

  const labels = {
	FORWARD: 'Arduino moved forward',
	REVERSE: 'Arduino moved in reverse',
	LEFT: 'Arduino turned left',
	RIGHT: 'Arduino turned right',
	STOP: 'Arduino stopped',
  };

  // formula.js is loaded globally, so do nothing on pages without the controller.
  if (!statusFeed || !lastCommand || !connection || !movementLog) return;

  let socket = null;
  let reconnectTimer = null;
  let isConnecting = false;

  const setConnectionState = (state, text) => {
	connection.dataset.state = state;
	connection.textContent = text;
  };

  const readMovements = () => {
	try {
	  return JSON.parse(localStorage.getItem(storageKey) || '[]');
	} catch {
	  return [];
	}
  };

  const drawMovements = (movements) => {
	movementLog.replaceChildren();

	if (!movements.length) {
	  const empty = document.createElement('p');
	  empty.className = 'formula-movement-empty';
	  empty.textContent = 'No movements recorded yet.';
	  movementLog.append(empty);
	  return;
	}

	movements.forEach((movement) => {
	  const entry = document.createElement('div');
	  entry.className = 'formula-movement';

	  const description = document.createElement('strong');
	  description.textContent = movement.label;

	  const timestamp = document.createElement('time');
	  timestamp.dateTime = movement.iso;
	  timestamp.textContent = movement.time;

	  entry.append(description, timestamp);
	  movementLog.append(entry);
	});
  };

  const addMovement = (command) => {
	const now = new Date();
	const movements = [{
	  label: labels[command] || `Arduino received ${command}`,
	  iso: now.toISOString(),
	  time: now.toLocaleString('en-GB'),
	}, ...readMovements()].slice(0, 20);

	localStorage.setItem(storageKey, JSON.stringify(movements));
	drawMovements(movements);
  };

  const scheduleReconnect = () => {
	if (reconnectTimer !== null) return;
	reconnectTimer = window.setTimeout(() => {
	  reconnectTimer = null;
	  connect();
	}, 2000);
  };

  const connect = () => {
	if (isConnecting || socket?.readyState === WebSocket.OPEN) return;

	isConnecting = true;
	setConnectionState('connecting', 'Connecting to the car...');

	try {
	  socket = new WebSocket(wsUrl);
	} catch (error) {
	  console.error('Could not create WebSocket:', error);
	  isConnecting = false;
	  setConnectionState('disconnected', 'Disconnected from the car');
	  scheduleReconnect();
	  return;
	}

	socket.addEventListener('open', () => {
	  isConnecting = false;
	  setConnectionState('connected', 'Connected to the car');
	  statusFeed.textContent = `> [${new Date().toLocaleTimeString('en-GB')}] WebSocket connected`;
	});

	socket.addEventListener('close', () => {
	  isConnecting = false;
	  setConnectionState('disconnected', 'Disconnected from the car');
	  statusFeed.textContent = `> [${new Date().toLocaleTimeString('en-GB')}] WebSocket disconnected`;
	  scheduleReconnect();
	});

	socket.addEventListener('error', (error) => {
	  console.error('WebSocket error:', error);
	  socket.close();
	});
  };

  const sendCommand = (command) => {
	const payload = commands[command];

	if (!payload) return;
	if (!socket || socket.readyState !== WebSocket.OPEN) {
	  statusFeed.textContent = '> WebSocket is not connected';
	  return;
	}

	socket.send(payload);
	lastCommand.textContent = command;
	statusFeed.textContent = `> [${new Date().toLocaleTimeString('en-GB')}] Command sent: ${command} (${payload})`;
	addMovement(command);
  };

  document.querySelectorAll('[data-command]').forEach((button) => {
	button.addEventListener('pointerdown', (event) => {
	  event.preventDefault();
	  button.classList.add('pressed');
	  sendCommand(button.dataset.command);
	});

	['pointerup', 'pointercancel', 'pointerleave'].forEach((eventName) => {
	  button.addEventListener(eventName, () => button.classList.remove('pressed'));
	});
  });

  const keys = {
	ArrowUp: 'FORWARD',
	ArrowDown: 'REVERSE',
	ArrowLeft: 'LEFT',
	ArrowRight: 'RIGHT',
	' ': 'STOP',
  };

  document.addEventListener('keydown', (event) => {
	const command = keys[event.key];
	if (!command || event.repeat) return;
	event.preventDefault();
	sendCommand(command);
  });

  window.addEventListener('beforeunload', () => socket?.close());

  drawMovements(readMovements());
  connect();
})();
