const storageKey = "theme-preference";
const notification = document.getElementById("notification");
const notificationMessage = document.getElementById("notification-message");

// 🔹 Déclaration Énumeration 
const NotificationType = Object.freeze ({
  ERROR: "error",
  WARNING: "warning",
  SUCCESS: "success"
});

let theme = getColorPreference();

// 🔹 Écouter les changements du système
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", ({ matches: isDark }) => {
  theme = isDark ? "dark" : "light";
  setThemePreference();
});

// 🔹 Au chargement de la page on affiche les notifications en attente 
window.addEventListener("load", () => {
  if (notification.classList.contains("lazy-visible")) {
    notification.classList.remove("lazy-visible");
    showNotification();
  }
});

// 🔹 Appliquer le thème dès le chargement
reflectPreference();

// 🔹 Récupérer la préférence utilisateur
function getColorPreference() {
  return localStorage.getItem(storageKey) || 
  (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
};

// 🔹 Appliquer le thème sur la page + iframes
function reflectPreference() {
  document.documentElement.setAttribute("data-theme", theme);
  document.body.classList.toggle("dark", theme === "dark");
  document.body.classList.toggle("light", theme === "light");
  document.querySelector("#theme-toggle")?.setAttribute("aria-label", theme);
  
  // 🔹 Met à jour les iframes
  try {
    updateIframeTheme();
  } catch {

  }
}

// 🔹 Changer et enregistrer la préférence
function setThemePreference () {
  theme = theme === "light" ? "dark" : "light";
  localStorage.setItem(storageKey, theme);
  reflectPreference();
}

// 🔹 Ferme la notification
function closeNotification () {
  notification.classList.remove("visible");
}

// 🔹 Affiche la notification et la ferme au bout de 10s
function showNotification () {
  notification.classList.add("visible");
  setTimeout(closeNotification, 10000);
}

// 🔹 Définition et affichage de la notification
function setNotification (state, message) {
  notification.classList.remove("success", "error", "warning");
  notification.classList.add(state);
  notificationMessage.innerText = message;
  showNotification();
}