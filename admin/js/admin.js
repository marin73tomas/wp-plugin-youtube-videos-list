async function setUpCVLForm() {
  try {
    const form = document.querySelector("#cvl-bulk-form");
    form &&
      form
        .querySelector("#cvl-yt-submit")
        .addEventListener("click", async function (event) {
          event.preventDefault();
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
              console.log(data);
            }
          }
        });
  } catch (error) {
    console.error(error);
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
