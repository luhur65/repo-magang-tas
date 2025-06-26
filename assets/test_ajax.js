// Proses ini akan terasa seperti download langsung bagi pengguna.
$.ajax({
    url: url,
    method: 'POST',
    // dataType: 'json',
    data: $('#export-form').serialize(),
    xhrFields: {
        responseType: 'blob'
    },
    success: function (blob, status, xhr) {

      console.log("--- BLOK SUCCESS DIJALANKAN ---", "Status:", status);

      // ... (kode untuk menangani error JSON jika ada, tetap sama)
      const contentType = xhr.getResponseHeader('Content-Type');
      if (contentType.indexOf('json') > -1) {
          return;
      }

      // --- Proses Download Langsung (Otomatis) ---
      const filename = "Laporan_Penjualan.xlsx"; // Nama file statis

      const downloadUrl = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = downloadUrl;
      a.download = filename; // Langsung gunakan nama file statis
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(downloadUrl);
      document.body.removeChild(a);

      // const postData = $('#jqGrid').getGridParam("postData");
      // delete postData.export;

      $('#dialogElem').dialog('close');

    },
    error: function (xhr, status, error) {

      console.log("--- BLOK ERROR DIJALANKAN ---", "Status:", status, "Error:", error, "XHR Object:", xhr);
      // ... (kode error handler yang sama, menggunakan FileReader)
      const reader = new FileReader();
      reader.onload = function() {
          try {
              const errorData = JSON.parse(reader.result);
              alert("Gagal Export: " + errorData.error);
              console.error("Server Error:", errorData.error);
          } catch (e) {
              alert("Terjadi kesalahan yang tidak diketahui saat memproses file.");
              console.error("Failed to parse error response:", reader.result);
          }
      };
      reader.readAsText(xhr.response);
    }
});
