let form = document.querySelector(".loginForm");

form.addEventListener('submit',async(e)=>{
    e.preventDefault();

    const data = new FormData(form);

    const response = await fetch(`../Backend/auth/login.php`, {
        method: 'POST',
        body: data
    })
    const result = await response.json();

    if(result.success)
    {
        window.location.href = '../app.php'
    }
    else
    {
        let errorDisplay = document.querySelector('.error')
        errorDisplay.innerText = result.error
    }
    })
