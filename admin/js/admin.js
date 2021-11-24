async function setUpCVLForm() {
  const form = document.querySelector("#cvl-bulk-form");
  if (form) {
    const inputs = form.querySelectorAll("inputs");
    const submit = form.querySelector("#cvl-yt-submit");
    try {
      submit.addEventListener("click", async function (event) {
        event.preventDefault();
        form.style.opacity = 0.5;
        this.classList.add("sending");
        inputs.forEach((e) => (e.disabled = true));
        const id = form.querySelector("#yt-id");
        const body = JSON.stringify({
          yt_id: id.value,
        });
        if (id) {
          const response = await fetch(ajax_var.root + `cvl/v1/addvideos`, {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            credentials: "same-origin",
            "Content-Type": "application/json",
            "X-WP-Nonce": ajax_var.cvl_nonce, //It is important to send the nonce in this format and on the headers request section
            body,
          });
          const data = await response.json();
          if (data) {
            Swal.fire({
              icon: "success",
              title: "Success!",
              text: data,
            });
            this.classList.remove("sending");
            form.style.opacity = 1;
            inputs.forEach((e) => (e.disabled = false));

          }
        }
      });
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong: " + error,
      });
    }
  }
}

function cvlReady(callback) {
  // in case the document is already rendered
  if (document.readyState != "loading") callback();
  // modern browsers
  else if (document.addEventListener)
    document.addEventListener("DOMContentLoaded", callback);
  // IE <= 8
  else
    document.attachEvent("onreadystatechange", function () {
      if (document.readyState == "complete") callback();
    });
}

cvlReady(setUpCVLForm);
