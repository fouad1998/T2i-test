const URL_API = 'http://localhost:9090/api/';

let alarms = [];
let pickedAlarm = null;

let freeAlarmSide, pickedAlarmSide, detailSide, datetime;

/**
 * To render the unpicked alarms
 */
function renderAlarms() {
  const elements = [];
  for (const alarm of alarms) {
    const div = document.createElement('div');
    div.innerHTML = alarm.firstname;
    div.classList.add('ticket');
    div.addEventListener('dblclick', function (event) {
      event.stopPropagation();
      if (pickedAlarm) {
        alarms.push(pickedAlarm);
      }

      pickedAlarm = alarm;
      alarms = alarms.filter(a => a.id !== alarm.id);
      renderAlarms();
      renderPicked();
    });
    elements.push(div);
  }

  freeAlarmSide.innerHTML = '';
  if (elements.length > 0) {
    return freeAlarmSide.append(...elements);
  }
}

/**
 * To render the picked alarm
 */
function renderPicked() {
  pickedAlarmSide.innerHTML = '';
  detailSide.innerHTML = '';
  if (!pickedAlarm) {
    return;
  }

  const ticket = document.createElement('div');
  ticket.innerText = pickedAlarm.firstname;
  ticket.classList.add('ticket');
  pickedAlarmSide.append(ticket);

  let lastItem;
  const { firstname, lastname, address, email, phone, birthday } = pickedAlarm;
  const details = [firstname, lastname, address, email, phone, birthday];
  for (const detail of details) {
    const p = document.createElement('p');
    p.textContent = detail;
    detailSide.append(p);
    lastItem = p;
  }

  const diff = diffDays(birthday);
  if (diff === 0) {
    lastItem.classList.add('birthday');
  } else if (diff < 0 && diff >= -7) {
    lastItem.classList.add('coming-birthday');
  } else if (diff <= 7 && diff > 0) {
    lastItem.classList.add('passed-birthday');
  }
}

/**
 * Render loading indicator
 */
function renderLoading() {
  const div = document.createElement('div');
  div.innerHTML = 'Loading...';
  div.classList.add('loading');
  div.setAttribute('id', 'loading');
  document.body.appendChild(div);
}

/**
 * To remove loading indicator
 */
function unmountLoading() {
  const div = document.getElementById('loading');
  div.remove();
}

async function fetchAlarms() {
  try {
    renderLoading();
    const response = await fetch(URL_API + 'person');
    if (!response.ok) {
      return;
    }

    const rows = await response.json();
    // Add the rows missing in actual state
    for (const row of rows) {
      if (pickedAlarm?.id === row.id) {
        continue;
      }

      const index = alarms.findIndex(e => e.id === row.id);
      if (index !== -1) {
        alarms[index] = row;
      } else {
        alarms.push(row);
      }
    }

    // Remove the rows missing in the response
    alarms = alarms.filter(e => rows.find(r => r.id === e.id));
    pickedAlarm = rows.find(r => r.id === pickedAlarm?.id);

    renderPicked();
    renderAlarms();
  } catch (error) {
    console.error(error);
    alert('Une erreur lors de chargement');
  } finally {
    unmountLoading();
    setTimeout(fetchAlarms, 5000);
  }
}

async function fetchTimezone() {
  try {
    const response = await fetch(URL_API + 'person');
    if (!response.ok) {
      return;
    }

    const { timezone } = await response.json();
    function updateDateTime() {
      const now = new Date();
      const str = now.toLocaleString('fr-fr', {
        timeZone: timezone,
        hourCycle: 'h24',
        year: 'numeric',
        day: '2-digit',
        month: '2-digit',
        minute: '2-digit',
        hour: '2-digit',
      });
      datetime.innerHTML = str;
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
  } catch (error) {
    console.error(error);
    // Try again, silently ignore errors
    fetchTimezone();
  }
}

function diffDays(birthday) {
  const now = new Date();
  const dayofBirth = new Date(birthday);
  now.setHours(0, 0, 0, 0);
  now.setFullYear(now.getFullYear() - (now.getFullYear() - dayofBirth.getFullYear()));
  const timeDiff = now.getTime() - dayofBirth.getTime();

  return Math.round(timeDiff / (1000 * 3600 * 24));
}

window.addEventListener('DOMContentLoaded', function () {
  freeAlarmSide = document.getElementById('free-alarms');
  pickedAlarmSide = document.getElementById('picked-alarm');
  detailSide = document.getElementById('detail');
  datetime = document.getElementById('date-time-value');

  fetchTimezone();
  fetchAlarms();
});
