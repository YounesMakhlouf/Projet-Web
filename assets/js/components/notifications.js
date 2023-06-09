const navbar = document.querySelector("#navbar");
const header = document.querySelector("#header");
const notificationList = document.querySelector(".notiflist");

const userId = document.querySelector(".userid").textContent.trim();
console.log(userId);

// Fetch notifications data from the server
async function fetchNotifications(userId) {
  try {
    const response = await fetch(`/notification/user/${userId}`);
    const data = await response.json();

    if (Array.isArray(data) && data.length > 0) {
      data.forEach((notification) => {
        displayNotification(notification.content);
      });
    } else {
      displayNotification("No notifications yet, don't lose hope!");
    }
  } catch (error) {
    console.error(error);
    displayNotification(
      "Failed to fetch notifications. Please try again later."
    );
  }
}

// Display a notification in the list
function displayNotification(content) {
  const listItem = document.createElement("li");
  listItem.classList.add("dropdown-item");
  listItem.textContent = content;
  notificationList.appendChild(listItem);
}

// Get the user ID from the DOM
function getUserId() {
  const userIdElement = document.querySelector(".userid");
  return userIdElement ? userIdElement.textContent.trim() : null;
}

// Initialize the notification system
function initializeNotifications() {
  const userId = getUserId();
  if (userId) {
    fetchNotifications(userId);
  }
}

initializeNotifications();
