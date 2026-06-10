const SOLICITUDES_DB = readJsonData("solicitudes-db", []);
const CORRELATIVOS_DB = readJsonData("correlativos-db", []);
let solicitudSeleccionada = null;

const ANALISIS = {
  "suelos": {
    label: "Suelos",
    items: [
      { nombre: "Textura", tipo: "FÃ­sico" },
      { nombre: "Densidad aparente", tipo: "FÃ­sico" },
      { nombre: "Densidad real", tipo: "FÃ­sico" },
      { nombre: "Humedad gravimÃ©trica", tipo: "FÃ­sico" },
      { nombre: "Porosidad total", tipo: "FÃ­sico" },
      { nombre: "pH", tipo: "QuÃ­mico" },
      { nombre: "Materia orgÃ¡nica", tipo: "QuÃ­mico" },
      { nombre: "NitrÃ³geno total", tipo: "QuÃ­mico" },
      { nombre: "FÃ³sforo disponible", tipo: "QuÃ­mico" },
      { nombre: "Potasio intercambiable", tipo: "QuÃ­mico" },
      { nombre: "CIC (capacidad de intercambio catiÃ³nico)", tipo: "QuÃ­mico" },
    ],
    metodo: "Los anÃ¡lisis de suelos incluyen pruebas fÃ­sicas y quÃ­micas. Los anÃ¡lisis fÃ­sicos se ejecutan conforme a protocolos ASTM D422 y mÃ©todos gravimÃ©tricos normalizados; los quÃ­micos siguen normas AOAC y mÃ©todos Walkley-Black, Kjeldahl y extracciÃ³n con acetato de amonio.",
  },
  "suelo-fisico": {
    label: "Suelos FÃ­sico",
    items: [
      { nombre: "Textura" },
      { nombre: "Densidad aparente" },
      { nombre: "Densidad real" },
      { nombre: "Humedad gravimÃ©trica" },
      { nombre: "Porosidad total" },
    ],
    metodo: "Los anÃ¡lisis fÃ­sicos de suelo se ejecutan conforme a los protocolos ASTM D422 para textura y mÃ©todos gravimÃ©tricos normalizados para densidades y humedad. Cada muestra es identificada y trazada desde su recepciÃ³n hasta la entrega de resultados, con controles de calidad dobles por lote.",
  },
  "suelo-quimico": {
    label: "Suelo QuÃ­mico",
    items: [
      { nombre: "pH" },
      { nombre: "Materia orgÃ¡nica" },
      { nombre: "NitrÃ³geno total" },
      { nombre: "FÃ³sforo disponible" },
      { nombre: "Potasio intercambiable" },
      { nombre: "CIC (capacidad de intercambio catiÃ³nico)" },
    ],
    metodo: "AnÃ¡lisis quÃ­mico bajo normas AOAC y mÃ©todos Walkley-Black, Kjeldahl y extracciÃ³n con acetato de amonio. Los reactivos son de grado analÃ­tico certificado y el laboratorio opera con control de temperatura a 20 Â± 2Â°C.",
  },
  "foliares": {
    label: "Foliares",
    items: [
      { nombre: "NitrÃ³geno foliar" },
      { nombre: "FÃ³sforo foliar" },
      { nombre: "Potasio foliar" },
      { nombre: "Calcio y Magnesio" },
      { nombre: "Micronutrientes (Fe, Mn, Zn, Cu)" },
    ],
    metodo: "Las muestras foliares deben presentarse limpias, previamente secadas a 65Â°C por 48 horas y molidas a malla 40. Los anÃ¡lisis siguen los protocolos del Instituto Internacional de NutriciÃ³n de Plantas (IPNI) y la norma AOAC 965.09 para digestiÃ³n de tejidos.",
  },
  "cana": {
    label: "CaÃ±a",
    items: [
      { nombre: "Brix (jugo)" },
      { nombre: "Pol (sacarosa)" },
      { nombre: "Pureza" },
      { nombre: "Fibra bruta" },
      { nombre: "Humedad del bagazo" },
      { nombre: "Jugo extraÃ­do (%)" },
    ],
    metodo: "AnÃ¡lisis de caÃ±a conforme a los mÃ©todos ICUMSA y las normas de la industria azucarera guatemalteca. Las muestras deben procesarse dentro de las 4 horas posteriores al corte para evitar la inversiÃ³n enzimÃ¡tica de la sacarosa.",
  },
  "miel": {
    label: "Miel",
    items: [
      { nombre: "Humedad" },
      { nombre: "HMF (Hidroximetilfurfural)" },
      { nombre: "Actividad diastÃ¡sica" },
      { nombre: "SÃ³lidos solubles (Â°Brix)" },
      { nombre: "pH y acidez libre" },
    ],
    metodo: "AnÃ¡lisis de mieles bajo la norma CODEX STAN 12-1981 y mÃ©todos AOAC International. Se verifica el cumplimiento del Reglamento TÃ©cnico Centroamericano RTCA 67.04.40:07. Las muestras deben entregarse en frascos de vidrio Ã¡mbar sellados.",
  },
  "agua": {
    label: "Agua",
    items: [
      { nombre: "pH" },
      { nombre: "Conductividad elÃ©ctrica (CE)" },
      { nombre: "SÃ³lidos totales disueltos (STD)" },
      { nombre: "Dureza total (CaCOâ‚ƒ)" },
      { nombre: "Coliformes totales y fecales" },
      { nombre: "Nitratos / Nitritos" },
    ],
    metodo: "AnÃ¡lisis de agua para uso agrÃ­cola conforme a las normas COGUANOR NGO 29001 y mÃ©todos estÃ¡ndar APHA-AWWA-WEF (Standard Methods for the Examination of Water and Wastewater, 23Âª ediciÃ³n). Las muestras deben recolectarse en frascos estÃ©riles y entregarse refrigeradas (4Â°C) en un mÃ¡ximo de 6 horas.",
  },
};

function readJsonData(id, fallback) {
  const script = document.getElementById(id);
  if (!script) return fallback;

  try {
    return JSON.parse(script.textContent || "");
  } catch {
    return fallback;
  }
}

function setTipoFormulario(tipo) {
  const input = document.getElementById("tipo_form");
  if (input) input.value = tipo;
}

function renderAnalisis(tipo) {
  const data = ANALISIS[tipo];
  const body = document.getElementById("analisis-body");
  if (!data || !body) return;

  setTipoFormulario(tipo);
  body.innerHTML = data.items.map((item, i) => `
    <tr>
      <td class="name">${item.nombre}</td>
      <td class="center"><span class="analisis-tag">${item.tipo || data.label}</span></td>
      <td class="center check-cell">
        <input type="checkbox" name="analisis[]" value="${item.nombre}" id="chk-${tipo}-${i}" aria-label="Solicitar ${item.nombre}"/>
      </td>
    </tr>
  `).join("");
  document.getElementById("metodo-box").textContent = data.metodo;
  document.getElementById("tipo-label-header").textContent = data.label;
  updateNumeroLaboratorio();
}

function getInicialTipo(tipo) {
  if (solicitudSeleccionada?.prefijo) {
    return solicitudSeleccionada.prefijo.toUpperCase();
  }

  const iniciales = {
    "suelos": "S",
    "suelo-fisico": "S",
    "suelo-quimico": "S",
    "foliares": "F",
    "cana": "C",
    "miel": "M",
    "agua": "A",
  };

  return iniciales[tipo] || "S";
}

function formatLote(numero, longitud) {
  return String(numero).padStart(longitud, "0");
}

function getMesAnio(fecha) {
  if (!fecha) return "";

  const partes = fecha.split("-");
  if (partes.length < 2) return "";

  return `${partes[1]}-${partes[0].slice(-2)}`;
}

function getSiguienteNumeroPorPrefijo(prefijo) {
  const correlativo = CORRELATIVOS_DB.find(item => String(item.prefijo || "").toUpperCase() === String(prefijo || "").toUpperCase());
  return correlativo ? parseInt(correlativo.ultimo_numero, 10) + 1 : 492;
}

function updateNumeroLaboratorio() {
  const tipoActivo = document.querySelector(".tipo-btn.active")?.dataset.tipo || "suelos";
  const fecha = document.getElementById("fecha_muestreo")?.value || "";
  const muestras = parseInt(document.getElementById("numero_muestras")?.value, 10);
  const inicioInput = document.getElementById("n_laboratorio_inicio");
  const finInput = document.getElementById("n_laboratorio_fin");
  const ocultoInput = document.getElementById("n_laboratorio");

  if (!inicioInput || !finInput || !ocultoInput) return;

  const inicial = getInicialTipo(tipoActivo);
  const inicioGuardado = solicitudSeleccionada?.inicio_laboratorio ? parseInt(solicitudSeleccionada.inicio_laboratorio, 10) : null;
  const loteInicial = inicioGuardado || getSiguienteNumeroPorPrefijo(inicial);
  const mesAnio = getMesAnio(fecha);

  if (Number.isNaN(loteInicial) || !mesAnio || Number.isNaN(muestras) || muestras < 1) {
    inicioInput.value = "";
    finInput.value = "";
    ocultoInput.value = "";
    return;
  }

  const longitudLote = Math.max(3, String(loteInicial).length);
  const loteFinal = loteInicial + muestras - 1;
  const codigoInicio = `${inicial}-${formatLote(loteInicial, longitudLote)}-${mesAnio}`;
  const codigoFin = `${inicial}-${formatLote(loteFinal, longitudLote)}-${mesAnio}`;

  inicioInput.value = codigoInicio;
  finInput.value = codigoFin;
  ocultoInput.value = `${codigoInicio} / ${codigoFin}`;
}

function setCamposReadonly(readonly) {
  ["numero_de_muestra", "lote", "fecha_muestreo", "numero_muestras"].forEach(id => {
    const input = document.getElementById(id);
    if (input) input.readOnly = readonly;
  });
}

function getTipoPorPrefijo(prefijo) {
  const tipos = {
    "S": "suelos",
    "F": "foliares",
    "C": "cana",
    "M": "miel",
    "A": "agua",
  };

  return tipos[String(prefijo || "").toUpperCase()] || null;
}

function seleccionarTipoPorSolicitud(solicitud) {
  const tipo = getTipoPorPrefijo(solicitud?.prefijo);
  if (!tipo || !ANALISIS[tipo]) return;

  const btn = document.querySelector(`.tipo-btn[data-tipo="${tipo}"]`);
  if (btn) {
    document.querySelectorAll(".tipo-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  }

  renderAnalisis(tipo);
}

function aplicarSolicitudDb(idSolicitud) {
  solicitudSeleccionada = SOLICITUDES_DB.find(solicitud => String(solicitud.id_solicitud) === String(idSolicitud)) || null;

  if (!solicitudSeleccionada) {
    setCamposReadonly(false);
    updateNumeroLaboratorio();
    return;
  }

  seleccionarTipoPorSolicitud(solicitudSeleccionada);
  document.getElementById("numero_de_muestra").value = solicitudSeleccionada.codigo_muestreo || "";
  document.getElementById("lote").value = solicitudSeleccionada.codigo_lote || "";
  document.getElementById("fecha_muestreo").value = solicitudSeleccionada.fecha_muestreo || "";
  document.getElementById("numero_muestras").value = solicitudSeleccionada.numero_muestras || "";
  setCamposReadonly(true);
  updateNumeroLaboratorio();
}

function initTipoButtons() {
  const tipoBtns = document.getElementById("tipo-btns");
  if (!tipoBtns) return;

  tipoBtns.addEventListener("click", event => {
    const btn = event.target.closest(".tipo-btn");
    if (!btn) return;

    document.querySelectorAll(".tipo-btn").forEach(item => item.classList.remove("active"));
    btn.classList.add("active");
    renderAnalisis(btn.dataset.tipo);
  });
}

function initLaboratorioInputs() {
  ["lote", "fecha_muestreo", "numero_muestras"].forEach(id => {
    const input = document.getElementById(id);
    if (input) input.addEventListener("input", updateNumeroLaboratorio);
  });
}

function initSolicitudSelect() {
  const solicitudSelect = document.getElementById("solicitud_registrada");
  if (solicitudSelect) {
    solicitudSelect.addEventListener("change", () => aplicarSolicitudDb(solicitudSelect.value));
  }

  return solicitudSelect;
}

function initTipoDesdeQuery() {
  const params = new URLSearchParams(window.location.search);
  const aliasTipos = {
    "suelo-fisico": "suelos",
    "suelo-quimico": "suelos",
  };
  const qOriginal = params.get("tipo");
  const q = aliasTipos[qOriginal] || qOriginal;

  if (!q || !ANALISIS[q]) {
    renderAnalisis("suelos");
    return;
  }

  const tiposCont = document.getElementById("tipo-btns");
  if (tiposCont) tiposCont.style.display = "none";

  const btn = document.querySelector(`.tipo-btn[data-tipo="${q}"]`);
  if (btn) {
    document.querySelectorAll(".tipo-btn").forEach(item => item.classList.remove("active"));
    btn.classList.add("active");
  }

  renderAnalisis(q);
}

function initSolicitudDesdeQuery(solicitudSelect) {
  const params = new URLSearchParams(window.location.search);
  const idSolicitud = params.get("id_solicitud");

  if (idSolicitud && solicitudSelect) {
    solicitudSelect.value = idSolicitud;
    aplicarSolicitudDb(idSolicitud);
  }
}

function makeDrawable(canvasId) {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  function resize() {
    const parent = canvas.parentElement;
    const rect = parent.getBoundingClientRect();
    const parentStyles = window.getComputedStyle(parent);
    const paddingX = parseFloat(parentStyles.paddingLeft) + parseFloat(parentStyles.paddingRight);
    const canvasWidth = rect.width - paddingX;
    canvas.style.height = "";
    const styles = window.getComputedStyle(canvas);
    const canvasHeight = parseFloat(styles.height) || 90;
    const ratio = window.devicePixelRatio || 1;
    const snapshot = canvas.toDataURL();

    canvas.width = canvasWidth * ratio;
    canvas.height = canvasHeight * ratio;
    canvas.style.width = canvasWidth + "px";
    canvas.style.height = canvasHeight + "px";

    const ctx = canvas.getContext("2d");
    ctx.scale(ratio, ratio);
    ctx.strokeStyle = "#27500A";
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.lineJoin = "round";

    const img = new Image();
    img.onload = () => ctx.drawImage(img, 0, 0, canvasWidth, canvasHeight);
    img.src = snapshot;
  }

  resize();
  window.addEventListener("resize", resize);

  const ctx = canvas.getContext("2d");
  let drawing = false;
  let lx = 0;
  let ly = 0;

  function getPos(event) {
    const rect = canvas.getBoundingClientRect();
    const src = event.touches ? event.touches[0] : event;
    return {
      x: src.clientX - rect.left,
      y: src.clientY - rect.top,
    };
  }

  canvas.addEventListener("pointerdown", event => {
    drawing = true;
    const position = getPos(event);
    lx = position.x;
    ly = position.y;
    canvas.setPointerCapture(event.pointerId);
  });

  canvas.addEventListener("pointermove", event => {
    if (!drawing) return;

    const position = getPos(event);
    ctx.beginPath();
    ctx.moveTo(lx, ly);
    ctx.lineTo(position.x, position.y);
    ctx.stroke();
    lx = position.x;
    ly = position.y;
  });

  canvas.addEventListener("pointerup", () => drawing = false);
  canvas.addEventListener("pointercancel", () => drawing = false);
}

function clearCanvas(id) {
  const canvas = document.getElementById(id);
  if (!canvas) return;
  canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
}

function initFirmas() {
  makeDrawable("canvas-ingreso");
  makeDrawable("canvas-recibe");

  document.querySelectorAll("[data-clear-canvas]").forEach(button => {
    button.addEventListener("click", () => clearCanvas(button.dataset.clearCanvas));
  });
}

initTipoButtons();
initLaboratorioInputs();
const solicitudSelect = initSolicitudSelect();
initTipoDesdeQuery();
initSolicitudDesdeQuery(solicitudSelect);
initFirmas();
