(() => {
    "use strict";

    // Enquiry page: open WhatsApp with the chosen categories and details already typed in.
    const waBtn = document.querySelector("[data-wa-send]");
    const enquiryForm = document.querySelector("[data-enquiry-form]");
    if (waBtn && enquiryForm) {
        const waError = document.querySelector("[data-wa-error]");
        const val = (name) => (enquiryForm.elements[name]?.value || "").trim();

        waBtn.addEventListener("click", () => {
            const cats = [
                ...enquiryForm.querySelectorAll(
                    'input[name="categories[]"]:checked',
                ),
            ].map((i) => i.closest("label").textContent.trim());

            if (!cats.length) {
                if (waError) waError.hidden = false;
                return;
            }
            if (waError) waError.hidden = true;

            const lines = [
                "Hello PROMON LTD, I would like to enquire about:",
                "",
                "Categories: " + cats.join(", "),
            ];
            const extra = [
                ["Business", val("business_name")],
                ["Name", val("contact_name")],
                ["City", val("city")],
                ["Country", val("country")],
            ].filter(([, v]) => v);
            if (extra.length)
                lines.push("", ...extra.map(([k, v]) => k + ": " + v));
            if (val("message")) lines.push("", val("message"));

            const url =
                "https://wa.me/" +
                waBtn.dataset.waNumber +
                "?text=" +
                encodeURIComponent(lines.join("\n"));
            window.open(url, "_blank", "noopener");
        });

        enquiryForm.addEventListener("change", (e) => {
            if (e.target.name === "categories[]" && waError)
                waError.hidden = true;
        });
    }

    // Mobile menu
    const toggle = document.querySelector("[data-menu-toggle]");
    const menu = document.querySelector("[data-menu]");
    if (!toggle || !menu) return;

    const setOpen = (open) => {
        toggle.setAttribute("aria-expanded", String(open));
        menu.classList.toggle("is-open", open);
    };

    toggle.addEventListener("click", () =>
        setOpen(toggle.getAttribute("aria-expanded") !== "true"),
    );
    menu.addEventListener("click", (e) => {
        if (e.target.closest("a")) setOpen(false);
    });
    document.addEventListener("keydown", (e) => {
        if (
            e.key === "Escape" &&
            toggle.getAttribute("aria-expanded") === "true"
        ) {
            setOpen(false);
            toggle.focus();
        }
    });
    window.matchMedia("(min-width: 1021px)").addEventListener("change", (m) => {
        if (m.matches) setOpen(false);
    });
})();
