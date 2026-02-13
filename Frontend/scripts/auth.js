let form = document.querySelector(".authForm");

async function callAPI(path, data) {
  const response = await fetch(path, {
    method: "POST",
    body: data,
  });
  const result = await response.json();
  return result;
}

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const data = new FormData(form);

  if (e.submitter.name == "Register") {
    let result = await callAPI(`../Backend/auth/register.php`, data);
    if (result.success) {
      window.location.href = "../app.php";
    } else {
      let errorDisplay = document.querySelector(".error");
      errorDisplay.innerHTML = result.error;
      setTimeout(() => {
        window.location.href = "login.html";
      }, 3000);
    }
  }

  if (e.submitter.name == "Login") {
    let result = await callAPI(`../Backend/auth/login.php`, data);
    if (result.success) {
      window.location.href = "../app.php";
    } else {
      let errorDisplay = document.querySelector(".error");
      errorDisplay.innerText = result.error;
    }
  }
});
