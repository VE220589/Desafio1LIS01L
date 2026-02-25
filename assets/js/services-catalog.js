document.addEventListener("DOMContentLoaded", () => {
  const cartCount = document.getElementById("cart-count");
  const cartSubtotal = document.getElementById("cart-subtotal");
  const cartItemsContainer = document.getElementById("cart-items");
  const clearCartBtn = document.getElementById("clear-cart");

  const generateQuoteBtn = document.getElementById("generate-quote");
  const quoteModal = new bootstrap.Modal(document.getElementById("quoteModal"));
  const modalSummary = document.getElementById("modal-summary");
  const quoteForm = document.getElementById("quote-form");

  async function loadCart() {
    const response = await fetch("../api/get-cart.php");
    const data = await response.json();

    cartCount.textContent = data.totalItems;
    cartSubtotal.textContent = data.total.toFixed(2);

    if (data.items.length === 0) {
      cartItemsContainer.innerHTML = "<p class='text-muted'>Carrito vacío.</p>";
      return;
    }

    let html = "";

    data.items.forEach((item) => {
      let disabledPlus = item.cantidad >= 10 ? "disabled" : "";
      let disabledMinus = item.cantidad <= 1 ? "disabled" : "";

      html += `
          <div class="border-bottom mb-3 pb-2">
              <strong>${item.nombre}</strong><br>
              $${item.precio.toFixed(2)} x ${item.cantidad}
              
              <div class="mt-2">
                  <button class="btn btn-sm btn-success increase" data-id="${item.id}" ${disabledPlus}>+</button>
                  <button class="btn btn-sm btn-warning decrease" data-id="${item.id}" ${disabledMinus}>-</button>
                  <button class="btn btn-sm btn-danger remove" data-id="${item.id}">Eliminar</button>
              </div>
          </div>
      `;
    });

    html += `
            <hr>
            <p>Subtotal: $${data.subtotal.toFixed(2)}</p>
            <p>Descuento: $${data.descuento.toFixed(2)}</p>
            <p>IVA: $${data.iva.toFixed(2)}</p>
            <h5>Total: $${data.total.toFixed(2)}</h5>
        `;

    cartItemsContainer.innerHTML = html;

    addCartEventListeners();
  }

  function addCartEventListeners() {
    document.querySelectorAll(".increase").forEach((btn) => {
      btn.addEventListener("click", async () => {
        await fetch("../api/update-cart.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${btn.dataset.id}&action=increase`,
        });
        loadCart();
      });
    });

    document.querySelectorAll(".decrease").forEach((btn) => {
      btn.addEventListener("click", async () => {
        await fetch("../api/update-cart.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${btn.dataset.id}&action=decrease`,
        });
        loadCart();
      });
    });

    document.querySelectorAll(".remove").forEach((btn) => {
      btn.addEventListener("click", async () => {
        await fetch("../api/remove-item.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${btn.dataset.id}`,
        });
        loadCart();
      });
    });
  }

  document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", async () => {
      await fetch("../api/add-to-cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${button.dataset.id}`,
      });
      loadCart();
    });
  });

  clearCartBtn.addEventListener("click", async () => {
    await fetch("../api/remove-from-cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `clear=true`,
    });
    loadCart();
  });

  loadCart();

  generateQuoteBtn.addEventListener("click", async () => {
    const response = await fetch("../api/get-cart.php");
    const data = await response.json();

    if (data.items.length === 0) {
      alert("El carrito está vacío.");
      return;
    }

    modalSummary.innerHTML = `
        <p><strong>Subtotal:</strong> $${data.subtotal.toFixed(2)}</p>
        <p><strong>Descuento:</strong> $${data.descuento.toFixed(2)}</p>
        <p><strong>IVA:</strong> $${data.iva.toFixed(2)}</p>
        <h5>Total: $${data.total.toFixed(2)}</h5>
    `;

    quoteModal.show();
  });

  quoteForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(quoteForm);

    const response = await fetch("../api/process-quote.php", {
      method: "POST",
      body: new URLSearchParams(formData),
    });

    const data = await response.json();

    if (data.success) {
      alert("Cotización generada correctamente.");

      quoteModal.hide();
      quoteForm.reset();
      loadCart();
    } else {
      alert(data.message);
    }
  });
});
