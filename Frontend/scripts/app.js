window.addEventListener("DOMContentLoaded", setup);

async function setup() {
  const authenticate = await fetch("../Backend/auth/check.php");
  const authResult = await authenticate.json();
  if (!authResult.success) {
    window.location.href = "login.html";
    return;
  }

  await displayEmails();
  const mailBtn = document.querySelector(".send");
  const updateBtn = document.querySelector(".update");
  const deleteBtn = document.querySelector(".delete");
  mailBtn.addEventListener("click", sendMail);
  updateBtn.addEventListener("click", updateMail);
  deleteBtn.addEventListener("click", deleteMail);
}

async function displayEmails() {
  const emails = await fetch("../Backend/crud/read.php");
  const emailsResult = await emails.json();
  let list = document.querySelector(".table");
  list.innerHTML = "<tr><th>Select</th><th>Email</th></tr>";
  let mailList = emailsResult.message;
  if (mailList.length == 0) {
    list.innerHTML = "No Emails Exist";
    return;
  }
  mailList.forEach((element) => {
    const tr = document.createElement("tr");
    tr.innerHTML =
      "<td><input type='checkbox' name='"
      + element.emails
      + "' class='is-checked'></td><td>"
      + element.emails
      + "</td>";
    list.appendChild(tr);
  });

  const mailBtn = document.querySelector(".send");
  const updateBtn = document.querySelector(".update");
  const deleteBtn = document.querySelector(".delete");

  function syncButtons() {
    const anyChecked =
      document.querySelectorAll(".is-checked:checked").length > 0;
    mailBtn.disabled = !anyChecked;
    updateBtn.disabled = !anyChecked;
    deleteBtn.disabled = !anyChecked;
  }

  function checkboxListeners() {
    document.querySelectorAll(".is-checked").forEach((checkbox) => {
      checkbox.addEventListener("change", syncButtons);
    });
    syncButtons();
  }

  checkboxListeners();
}

const overlay = document.querySelector(".overlay");
const dialogue = document.querySelector(".dialog");

const insert = document.querySelector(".insert");

insert.addEventListener("submit", async (e) => {
  e.preventDefault();

  const data = new FormData(insert);
  insert.reset();
  const response = await fetch("../Backend/crud/insert.php", {
    method: "POST",
    body: data,
  });
  const result = await response.json();
  await displayEmails();
});

function updateMail() {
  dialogue.innerHTML = `<p class="dialog-progress"></p>
  <p class="dialog-email"></p>
  <form class="update-form">
  <input type="email" placeholder="Replacement email" name="email" required>
  <div class="dialog-buttons">
  <button type="submit" class="form-btn">Update</button>
  <button type="button" class="form-btn skip">Skip</button>
  <button type="button" class="form-btn cancel">Cancel All</button>
  </div>
  </form>`;

  overlay.style.display = "flex";

  const dialogueCount = document.querySelector(".dialog-progress");
  const dialogueEmail = document.querySelector(".dialog-email");
  const updateForm = document.querySelector(".update-form");
  const skipBtn = document.querySelector(".skip");
  const cancelBtn = document.querySelector(".cancel");
  const checkedBoxes = document.querySelectorAll(".is-checked:checked");

  let request = [];
  let index = 0;

  async function updateAPICall() {
    const response = await fetch("../Backend/crud/update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(request),
    });
    const result = await response.json();
    await displayEmails();
  }

  function currentEmail() {
    const length = checkedBoxes.length;

    if (index >= length) {
      updateAPICall();
      overlay.style.display = "none";
      return;
    }

    const email = checkedBoxes.item(index).name;
    dialogueCount.textContent = index + 1 + "/" + length;
    dialogueEmail.textContent = email;
    updateForm.reset();
  }
  updateForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const data = new FormData(updateForm);
    const oldEmail = checkedBoxes.item(index).name;
    const newEmail = data.get("email");
    request.push({ oldEmail: oldEmail, replacementEmail: newEmail });
    index++;
    currentEmail();
  });

  skipBtn.addEventListener("click", () => {
    index++;
    currentEmail();
  });

  cancelBtn.addEventListener("click", () => {
    request = [];
    overlay.style.display = "none";
  });

  currentEmail();
}

function deleteMail() {
  dialogue.innerHTML = `<p class="dialog-progress"></p>
  <div class="delete-confirm">
    <p class="delete-warning">This action cannot be reverted.</p>
  </div>
  <div class="dialog-buttons">
    <button type="button" class="form-btn delete-btn">Delete</button>
    <button type="button" class="form-btn cancel">Cancel</button>
  </div>`;
  dialogue.classList.add("delete-dialog");
  overlay.style.display = "flex";

  const dialogueCount = document.querySelector(".dialog-progress");
  const deleteBtn = document.querySelector(".delete-btn");
  const cancelBtn = document.querySelector(".cancel");
  const checkedBoxes = document.querySelectorAll(".is-checked:checked");
  const length = checkedBoxes.length;
  dialogueCount.innerText = "Deleting " + length + " Emails";

  deleteBtn.addEventListener("click", async () => {
    let request = [];
    checkedBoxes.forEach((box) => {
      request.push(box.name);
    });
    const response = await fetch("../Backend/crud/delete.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(request),
    });
    const result = await response.json();
    await displayEmails();
    dialogue.classList.remove("delete-dialog");
    overlay.style.display = "none";
  });

  cancelBtn.addEventListener("click", () => {
    dialogue.classList.remove("delete-dialog");
    overlay.style.display = "none";
  });
}

function sendMail() {
  dialogue.innerHTML = `<p class="dialog-progress"></p>
  <form class="send-form">
  <input type="text" placeholder="Email Subject" name="subject" required>
  <textarea type="email" placeholder="Email body" name="email-body" required></textarea>
  <div class="dialog-buttons">
  <button type="submit" class="form-btn send-btn">Send</button>
  <button type="button" class="form-btn cancel">Cancel</button>
  </div>
  </form>`;
  dialogue.classList.add("send-dialog");
  overlay.style.display = "flex";

  const sendForm = document.querySelector(".send-form");
  const dialogueCount = document.querySelector(".dialog-progress");
  const updateForm = document.querySelector(".send-form");
  const cancelBtn = document.querySelector(".cancel");
  const sendBtn = document.querySelector(".send-btn");
  const checkedBoxes = document.querySelectorAll(".is-checked:checked");
  dialogueCount.innerText = "Mailing " + checkedBoxes.length + " Recipients";

  sendForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const request = [];
    const data = new FormData(updateForm);
    const subject = data.get("subject");
    const emailBody = data.get("email-body");
    checkedBoxes.forEach((box) => {
      request.push(box.name);
    });
    const response = await fetch("../Backend/mail/send.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        subject: subject,
        body: emailBody,
        recipients: request,
      }),
    });
    sendBtn.disabled = true;
    cancelBtn.disabled = true;
    const result = await response.json();
    await displayEmails();
    dialogue.classList.remove("send-dialog");
    overlay.style.display = "none";
  });

  cancelBtn.addEventListener("click", () => {
    dialogue.classList.remove("send-dialog");
    overlay.style.display = "none";
  });
}
