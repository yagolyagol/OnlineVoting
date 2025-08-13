document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".password-wrapper").forEach(wrapper => {
        const input = wrapper.querySelector("input[type='password'], input[type='text']");
        const toggleBtn = wrapper.querySelector(".toggle-password");

        toggleBtn.addEventListener("click", () => {
            if (input.type === "password") {
                input.type = "text";
                toggleBtn.textContent = "🙈"; // password visible
            } else {
                input.type = "password";
                toggleBtn.textContent = "👁️"; // password hidden
            }
        });
    });
});
