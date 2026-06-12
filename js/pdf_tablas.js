(function () {
  const green = {
    dark: rgb(0.153, 0.314, 0.039),
    mid: rgb(0.388, 0.6, 0.133),
    light: rgb(0.918, 0.953, 0.871),
    border: rgb(0.784, 0.859, 0.659),
    text: rgb(0.102, 0.204, 0.024),
    muted: rgb(0.353, 0.439, 0.271),
    white: rgb(1, 1, 1),
  };

  function rgb(r, g, b) {
    return window.PDFLib.rgb(r, g, b);
  }

  function normalizarTexto(value) {
    return String(value ?? "").replace(/\s+/g, " ").trim() || "-";
  }

  function nombreArchivo(value) {
    return normalizarTexto(value)
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-zA-Z0-9._-]+/g, "_")
      .replace(/^_+|_+$/g, "")
      .toLowerCase();
  }

  function descargar(bytes, fileName) {
    const blob = new Blob([bytes], { type: "application/pdf" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
  }

  function wrapText(text, font, size, maxWidth) {
    const words = normalizarTexto(text).split(" ");
    const lines = [];
    let line = "";

    words.forEach((word) => {
      const candidate = line ? `${line} ${word}` : word;
      if (font.widthOfTextAtSize(candidate, size) <= maxWidth || !line) {
        line = candidate;
      } else {
        lines.push(line);
        line = word;
      }
    });

    if (line) lines.push(line);
    return lines;
  }

  async function cargarLogo(pdfDoc) {
    try {
      const response = await fetch("../../assets/Marca%20Cengica%C3%B1a/SinFondo_logo_cengicana_Vertical.png", { cache: "no-store" });
      if (!response.ok) return null;
      return await pdfDoc.embedPng(await response.arrayBuffer());
    } catch (_) {
      return null;
    }
  }

  async function crearPdf({ titulo, subtitulo, resumen = [], headers, rows, fileName }) {
    if (!window.PDFLib) {
      alert("No se pudo cargar la librería para generar PDF.");
      return;
    }

    const { PDFDocument, StandardFonts } = window.PDFLib;
    const pdfDoc = await PDFDocument.create();
    const bold = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
    const regular = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const logo = await cargarLogo(pdfDoc);
    const pageSize = [842, 595];
    const margin = 34;
    const rowMinHeight = 24;
    let page = pdfDoc.addPage(pageSize);
    let y = 548;

    function header() {
      page.drawRectangle({ x: margin, y: 508, width: 774, height: 54, color: green.light, borderColor: green.border, borderWidth: 1 });
      if (logo) page.drawImage(logo, { x: margin + 12, y: 516, width: 34, height: 38 });
      page.drawText("CENGICAÑA", { x: margin + 56, y: 540, size: 10, font: bold, color: green.dark });
      page.drawText(titulo, { x: margin + 56, y: 524, size: 16, font: bold, color: green.text });
      page.drawText(subtitulo, { x: margin + 56, y: 512, size: 9, font: regular, color: green.muted });
      y = 490;
    }

    function newPage() {
      page = pdfDoc.addPage(pageSize);
      header();
    }

    header();

    if (resumen.length) {
      const chipWidth = 180;
      resumen.forEach((item, index) => {
        const x = margin + (index % 4) * (chipWidth + 10);
        const chipY = y - Math.floor(index / 4) * 34;
        page.drawRectangle({ x, y: chipY, width: chipWidth, height: 24, color: green.white, borderColor: green.border, borderWidth: 1 });
        page.drawText(`${item.label}:`, { x: x + 8, y: chipY + 9, size: 8, font: bold, color: green.dark });
        page.drawText(normalizarTexto(item.value), { x: x + 70, y: chipY + 9, size: 8, font: regular, color: green.text });
      });
      y -= Math.ceil(resumen.length / 4) * 34 + 8;
    }

    const tableWidth = 774;
    const colWidth = tableWidth / headers.length;

    function drawTableHeader() {
      page.drawRectangle({ x: margin, y: y - rowMinHeight, width: tableWidth, height: rowMinHeight, color: green.light, borderColor: green.border, borderWidth: 1 });
      headers.forEach((headerText, index) => {
        page.drawText(normalizarTexto(headerText), { x: margin + index * colWidth + 7, y: y - 15, size: 8, font: bold, color: green.dark });
      });
      y -= rowMinHeight;
    }

    drawTableHeader();

    rows.forEach((row) => {
      const wrapped = row.map((cell) => wrapText(cell, regular, 8, colWidth - 14));
      const height = Math.max(rowMinHeight, Math.max(...wrapped.map((lines) => lines.length)) * 10 + 12);
      if (y - height < margin) {
        newPage();
        drawTableHeader();
      }

      page.drawRectangle({ x: margin, y: y - height, width: tableWidth, height, color: green.white, borderColor: green.border, borderWidth: 1 });
      wrapped.forEach((lines, index) => {
        lines.slice(0, 5).forEach((line, lineIndex) => {
          page.drawText(line, { x: margin + index * colWidth + 7, y: y - 15 - lineIndex * 10, size: 8, font: regular, color: green.text });
        });
      });
      y -= height;
    });

    const bytes = await pdfDoc.save();
    descargar(bytes, fileName);
  }

  async function crearPdfBoletaLote({ lote, fileName }) {
    if (!window.PDFLib) {
      alert("No se pudo cargar la libreria para generar PDF.");
      return;
    }

    const { PDFDocument, StandardFonts } = window.PDFLib;
    const pdfDoc = await PDFDocument.create();
    const bold = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
    const regular = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const logo = await cargarLogo(pdfDoc);
    const pageSize = [595.28, 841.89];
    const margin = 44;
    const bottom = 46;
    const contentWidth = pageSize[0] - margin * 2;
    let page = pdfDoc.addPage(pageSize);
    let y = pageSize[1] - margin;

    function addHeader(continuacion = false) {
      if (logo) page.drawImage(logo, { x: margin, y: y - 48, width: 70, height: 44 });
      const textX = logo ? margin + 88 : margin;
      page.drawText("Laboratorio Agroindustrial", { x: textX, y, size: continuacion ? 13 : 18, font: bold, color: green.dark });
      page.drawText(continuacion ? "Boleta de lote - continuacion" : "Boleta de solicitud de analisis", { x: textX, y: y - 22, size: 10, font: regular, color: green.muted });
      page.drawRectangle({ x: pageSize[0] - margin - 78, y: y - 26, width: 78, height: 34, color: green.light, borderColor: green.border, borderWidth: 1 });
      page.drawText("VF", { x: pageSize[0] - margin - 64, y: y - 6, size: 8, font: bold, color: green.muted });
      page.drawText("005", { x: pageSize[0] - margin - 64, y: y - 20, size: 12, font: bold, color: green.dark });
      y -= 78;
    }

    function newPage() {
      page = pdfDoc.addPage(pageSize);
      y = pageSize[1] - margin;
      addHeader(true);
    }

    function ensureSpace(needed) {
      if (y - needed < bottom) newPage();
    }

    function drawSection(title) {
      ensureSpace(32);
      page.drawText(normalizarTexto(title).toUpperCase(), { x: margin, y, size: 9, font: bold, color: green.dark });
      page.drawLine({ start: { x: margin + 150, y: y + 3 }, end: { x: pageSize[0] - margin, y: y + 3 }, thickness: 0.8, color: green.border });
      y -= 20;
    }

    function drawWrapped(text, x, maxWidth, size = 9.5, font = regular, color = green.text, lineHeight = 13) {
      wrapText(text, font, size, maxWidth).forEach((line) => {
        ensureSpace(lineHeight + 2);
        page.drawText(line, { x, y, size, font, color });
        y -= lineHeight;
      });
    }

    function drawInfoRows(rows) {
      const gap = 12;
      const cellWidth = (contentWidth - gap) / 2;
      rows.forEach((row, index) => {
        if (index % 2 === 0) ensureSpace(46);
        const x = margin + (index % 2) * (cellWidth + gap);
        const topY = y;
        page.drawRectangle({ x, y: topY - 38, width: cellWidth, height: 38, borderColor: green.border, borderWidth: 0.8 });
        page.drawText(normalizarTexto(row[0]).toUpperCase(), { x: x + 8, y: topY - 13, size: 7, font: bold, color: green.muted });
        wrapText(row[1], regular, 9.5, cellWidth - 16).slice(0, 2).forEach((line, lineIndex) => {
          page.drawText(line, { x: x + 8, y: topY - 28 - lineIndex * 10, size: 9.5, font: regular, color: green.text });
        });
        if (index % 2 === 1 || index === rows.length - 1) y -= 46;
      });
    }

    addHeader(false);

    const solicitudes = Array.isArray(lote.solicitudes) ? lote.solicitudes : [];
    drawSection("Datos del lote");
    drawInfoRows([
      ["ID lote", lote.id],
      ["Numero de lote", lote.codigo_lote],
      ["Numero de laboratorio", lote.numeros_laboratorio],
      ["Solicitudes", solicitudes.length],
    ]);

    solicitudes.forEach((solicitud, index) => {
      drawSection(`Solicitud ${solicitud.id_solicitud || index + 1}`);
      drawInfoRows([
        ["Tipo de muestra", solicitud.tipo_muestra],
        ["Fecha de muestreo", solicitud.fecha_muestreo],
        ["Numero de muestras", solicitud.numero_muestras],
        ["Laboratorio inicio", solicitud.laboratorio_inicio],
        ["Laboratorio fin", solicitud.laboratorio_fin],
        ["Fecha ingreso", solicitud.fecha_ingreso],
        ["Fecha estimada", solicitud.fecha_estimada],
        ["Ingresado por", solicitud.ingresado_por],
        ["Recibido por", solicitud.recibido_por],
      ]);

      drawSection("Analisis solicitados");
      const analisis = Array.isArray(solicitud.analisis) ? solicitud.analisis : [];
      if (analisis.length) {
        analisis.forEach((item) => drawWrapped(`- ${item}`, margin, contentWidth, 10));
      } else {
        drawWrapped("Sin analisis registrados.", margin, contentWidth, 10, regular, green.muted);
      }

      drawSection("Observaciones");
      drawWrapped(solicitud.observaciones || "Sin observaciones.", margin, contentWidth, 10);
    });

    const bytes = await pdfDoc.save();
    descargar(bytes, fileName);
  }

  async function crearPdfConsolidacion({ titulo, subtitulo, analisisTitulo = "Analisis solicitados", headers, rows, fileName }) {
    if (!window.PDFLib) {
      alert("No se pudo cargar la libreria para generar PDF.");
      return;
    }

    const { PDFDocument, StandardFonts, rgb } = window.PDFLib;
    const pdfDoc = await PDFDocument.create();
    const bold = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
    const regular = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const pageSize = [842, 595];
    const margin = 24;
    const tableWidth = pageSize[0] - margin * 2;
    const baseCols = 5;
    const widths = headers.map((_, index) => index < baseCols ? [62, 54, 36, 36, 44][index] : 42);
    widths.push(78);
    const finalHeaders = headers.concat(["Observaciones"]);
    const totalWidth = widths.reduce((sum, width) => sum + width, 0);
    const scale = Math.min(1, tableWidth / totalWidth);
    const colWidths = widths.map((width) => width * scale);
    let page = pdfDoc.addPage(pageSize);
    let y = pageSize[1] - margin;

    function drawPageHeader() {
      page.drawText("CENGICANA", { x: margin, y: y - 36, size: 14, font: bold, color: rgb(0.55, 0.55, 0.55) });
      page.drawRectangle({ x: 255, y: y - 28, width: 310, height: 22, borderColor: rgb(0, 0, 0), borderWidth: 1 });
      page.drawText(normalizarTexto(titulo).toUpperCase(), { x: 285, y: y - 21, size: 10, font: bold, color: rgb(0, 0, 0) });
      page.drawText(`${analisisTitulo}:`, { x: 328, y: y - 60, size: 9, font: regular, color: rgb(0, 0, 0) });
      page.drawRectangle({ x: 422, y: y - 66, width: 150, height: 18, borderColor: rgb(0, 0, 0), borderWidth: 1 });
      page.drawText(normalizarTexto(subtitulo), { x: 428, y: y - 61, size: 8, font: regular, color: rgb(0, 0, 0) });
      y -= 92;
    }

    function drawTableHeader() {
      const headerTop = y;
      let x = margin;
      page.drawRectangle({ x, y: headerTop - 32, width: tableWidth, height: 32, borderColor: rgb(0, 0, 0), borderWidth: 1 });
      const chemicalX = margin + colWidths.slice(0, baseCols).reduce((sum, width) => sum + width, 0);
      const chemicalWidth = colWidths.slice(baseCols, finalHeaders.length - 1).reduce((sum, width) => sum + width, 0);
      if (chemicalWidth > 0) {
        page.drawText("QUIMICO", { x: chemicalX + Math.max(3, chemicalWidth / 2 - 18), y: headerTop - 10, size: 7, font: bold, color: rgb(0, 0, 0) });
      }
      finalHeaders.forEach((headerText, index) => {
        const width = colWidths[index];
        page.drawRectangle({ x, y: headerTop - 32, width, height: 32, borderColor: rgb(0, 0, 0), borderWidth: 0.7 });
        page.drawText(normalizarTexto(headerText).slice(0, 12), { x: x + 3, y: headerTop - 25, size: 6.2, font: bold, color: rgb(0, 0, 0) });
        x += width;
      });
      y -= 32;
    }

    function newPage() {
      page = pdfDoc.addPage(pageSize);
      y = pageSize[1] - margin;
      drawPageHeader();
      drawTableHeader();
    }

    drawPageHeader();
    drawTableHeader();

    (rows.length ? rows : [["-", "-", "-", "-", "-"]]).forEach((row) => {
      const pdfRow = row.concat([""]);
      const rowHeight = 18;
      if (y - rowHeight < margin) newPage();
      let x = margin;
      pdfRow.forEach((cell, index) => {
        const width = colWidths[index];
        const value = normalizarTexto(cell).replace("Si, completado", "Si").replace("Si", "SI");
        const color = value === "SI" ? rgb(1, 0.95, 0.2) : green.white;
        page.drawRectangle({ x, y: y - rowHeight, width, height: rowHeight, color, borderColor: rgb(0, 0, 0), borderWidth: 0.5 });
        page.drawText(value.slice(0, 14), { x: x + 3, y: y - 12, size: 6.5, font: regular, color: rgb(0, 0, 0) });
        x += width;
      });
      y -= rowHeight;
    });

    const bytes = await pdfDoc.save();
    descargar(bytes, fileName);
  }

  window.LabPdfTablas = { crearPdf, crearPdfBoletaLote, crearPdfConsolidacion, nombreArchivo, normalizarTexto };
})();
