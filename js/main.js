(() => {
  const btn = document.querySelector(".nav-toggle");
  const nav = document.getElementById("siteNav");
  if (!btn || !nav) return;

  btn.addEventListener("click", () => {
    const open = nav.classList.toggle("is-open");
    btn.setAttribute("aria-expanded", open ? "true" : "false");
});
})();
(() => {
  const toggles = document.querySelectorAll(".details__toggles .toggle-btn");
  if (!toggles.length) return;

  toggles.forEach(btn => {
    btn.addEventListener("click", () => {
      toggles.forEach(b => b.classList.remove("is-active"));
      btn.classList.add("is-active");
    });
  });
})();
(() => {
  const roleCards = document.querySelectorAll(".role-card");
  const signupForm = document.getElementById("signupForm");
  const roleInput = document.getElementById("roleInput");
  const roleSelectedText = document.getElementById("roleSelectedText");

  if (!roleCards.length || !signupForm || !roleInput || !roleSelectedText) return;

  roleCards.forEach(card => {
    card.addEventListener("click", () => {
      roleCards.forEach(c => c.classList.remove("is-selected"));
      card.classList.add("is-selected");

      const role = card.getAttribute("data-role") || "";
      roleInput.value = role;
      roleSelectedText.textContent = role || "None";

      signupForm.classList.remove("auth-form--hidden");
      signupForm.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });
})();
(() => {
  const btn = document.querySelector(".dash-toggle");
  const side = document.querySelector(".dash-side");
  if (!btn || !side) return;

  btn.addEventListener("click", () => {
    side.classList.toggle("is-open");
  });
})();
(() => {
  const btn = document.getElementById("calcBtn");
  const reset = document.getElementById("calcReset");

  const landSize = document.getElementById("landSize");
  const pricePerSqft = document.getElementById("pricePerSqft");
  const commission = document.getElementById("commission");
  const tax = document.getElementById("tax");
  const regFee = document.getElementById("regFee");
  const discount = document.getElementById("discount");

  const basePriceEl = document.getElementById("basePrice");
  const commissionEl = document.getElementById("commissionValue");
  const taxEl = document.getElementById("taxValue");
  const regEl = document.getElementById("regValue");
  const discountEl = document.getElementById("discountValue");
  const totalEl = document.getElementById("totalValue");

  if (!btn || !landSize || !pricePerSqft || !totalEl) return;

  const n = (v) => {
    const x = parseFloat(v);
    return Number.isFinite(x) ? x : 0;
  };

  const fmt = (v) =>
    new Intl.NumberFormat("en-BD", { maximumFractionDigits: 2 }).format(v) + " BDT";

  btn.addEventListener("click", () => {
    const size = n(landSize.value);
    const ppsf = n(pricePerSqft.value);
    const commPct = n(commission.value);
    const taxVal = n(tax.value);
    const regVal = n(regFee.value);
    const discVal = n(discount.value);

    const base = size * ppsf;
    const comm = (base * commPct) / 100;
    const total = base + comm + taxVal + regVal - discVal;

    basePriceEl.textContent = fmt(base);
    commissionEl.textContent = fmt(comm);
    taxEl.textContent = fmt(taxVal);
    regEl.textContent = fmt(regVal);
    discountEl.textContent = "- " + fmt(discVal);
    totalEl.textContent = fmt(Math.max(0, total));
  });

  if (reset) {
    reset.addEventListener("click", () => {
      [landSize, pricePerSqft, commission, tax, regFee, discount].forEach((el) => {
        if (el) el.value = "";
      });
      [basePriceEl, commissionEl, taxEl, regEl, discountEl, totalEl].forEach((el) => {
        if (el) el.textContent = "—";
      });
    });
  }
})();
(() => {
  const tabs = document.querySelectorAll(".pay-tab");
  const panels = document.querySelectorAll(".pay-panel");
  if (!tabs.length || !panels.length) return;

  const open = (name) => {
    tabs.forEach(t => t.classList.toggle("is-active", t.dataset.tab === name));
    panels.forEach(p => p.classList.toggle("is-open", p.dataset.panel === name));
  };

  tabs.forEach(tab => {
    tab.addEventListener("click", () => open(tab.dataset.tab));
  });

  open("card"); // default
})();
