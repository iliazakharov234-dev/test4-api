// Клиентская валидация локальной формы регистрации.
// Только локальные проверки, сеть не используется.

var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
var PASSWORD_MIN_LENGTH = 8;
var PASSWORD_MAX_LENGTH = 64;
var STORAGE_KEY = "registeredEmails";

// --- Хранилище зарегистрированных email (localStorage) ---

// Прочитать список зарегистрированных email (JSON-массив)
function loadEmails() {
  try {
    var raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) {
      return [];
    }
    var parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? parsed : [];
  } catch (error) {
    return [];
  }
}

// Сохранить список зарегистрированных email
function saveEmails(emails) {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(emails));
  } catch (error) {
    // localStorage недоступен — сохранение пропускается
  }
}

// Возвращает массив зарегистрированных email
function getAllEmails() {
  return loadEmails();
}

// Очищает хранилище (для тестов)
function clearEmails() {
  try {
    localStorage.removeItem(STORAGE_KEY);
  } catch (error) {
    // localStorage недоступен — очистка пропускается
  }
}

// Доступ из тестов: window.clearRegisteredEmails()
window.clearRegisteredEmails = clearEmails;

// --- Пометка полей и сообщения ---

// При невалидном поле — класс invalid, при валидном — valid
function markField(field, isValid) {
  field.classList.toggle("invalid", !isValid);
  field.classList.toggle("valid", isValid);
}

// Красное сообщение об ошибке
function showError(errorBox, text) {
  errorBox.hidden = false;
  errorBox.textContent = text;
  errorBox.classList.remove("success");
}

// Зелёное сообщение об успехе
function showSuccess(errorBox, text) {
  errorBox.hidden = false;
  errorBox.textContent = text;
  errorBox.classList.add("success");
}

document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("registrationForm");
  var errorBox = document.getElementById("errorBox");

  // При загрузке страницы читаем список email из localStorage
  var registeredEmails = loadEmails();

  form.addEventListener("submit", function (event) {
    // Не отправлять форму по-настоящему
    event.preventDefault();

    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var confirmPassword = document.getElementById("confirmPassword");

    // 1. Валидация email по regex
    var emailIsValid = EMAIL_PATTERN.test(email.value.trim());
    markField(email, emailIsValid);

    // 2. Строгая валидация пароля: от 8 до 64 символов
    var passwordLength = password.value.length;
    var passwordIsValid =
      passwordLength >= PASSWORD_MIN_LENGTH &&
      passwordLength <= PASSWORD_MAX_LENGTH;
    markField(password, passwordIsValid);

    // 3. Проверка совпадения пароля и подтверждения
    var confirmIsValid =
      confirmPassword.value.length > 0 && confirmPassword.value === password.value;
    markField(confirmPassword, confirmIsValid);

    var hasErrors = !(emailIsValid && passwordIsValid && confirmIsValid);
    if (hasErrors) {
      showError(errorBox, "Проверьте правильность заполнения полей");
      return;
    }

    // 4. Проверка дубликата email (сравнение в нижнем регистре)
    var emailValue = email.value.trim().toLowerCase();
    registeredEmails = loadEmails();
    if (registeredEmails.indexOf(emailValue) !== -1) {
      showError(errorBox, "Email уже зарегистрирован");
      return;
    }

    // 5. Успешная регистрация: добавить email в хранилище
    registeredEmails.push(emailValue);
    saveEmails(registeredEmails);
    showSuccess(errorBox, "Регистрация успешна");
  });
});
