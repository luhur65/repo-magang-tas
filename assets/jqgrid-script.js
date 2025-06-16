function tambahBarang() {

  $('#dialogElem').load('./app/views/penjualan/form-penjualan.php').dialog({
    modal: true, 
    height: 500,
    width: 1100,
    position: { my: "center center", at: "center", of: window },
    title: 'Tambah Data',

    buttons: {
      'Simpan Data': function () {
        let sortname = $('#jqGrid').jqGrid('getGridParam', 'sortname');
        let sortorder = $('#jqGrid').jqGrid('getGridParam', 'sortorder');
        let rowNum = parseInt($('#jqGrid').jqGrid('getGridParam', 'rowNum'));

        const noBukti = $("form [name=no_bukti").val();
        const tglBukti = $("form [name=tgl_bukti").val();
        const pelanggan = $("form [name=pelanggan]").val();

        // validasi form
        if (noBukti == "" || tglBukti == "" || pelanggan == 0) {
          Swal.fire({
            title: 'Error!',
            text: 'Ada data kosong!',
            icon: 'error',
          });

          return false;
        }

        // Unformat AutoNumeric sebelum serialize
        $('#data-form .harga, #data-form .qty').each(function () {
          const an = AutoNumeric.getAutoNumericElement(this);
          if (an) this.value = an.getNumber();
        });

        $.ajax({
          url: './app/data/penjualan.php?sortname=' + sortname + '&sortorder=' + sortorder,
          method: 'POST',
          dataType: 'JSON',
          data: $('#data-form').serialize(),
          success: function (data) {

            // notif saya
            Swal.fire({
              title: 'Berhasil!',
              text: 'Berhasil Ditambahkan',
              icon: 'success',
            });

            let id = data['id'];
            let countRow = data['count'];
            let page = Math.ceil(countRow / rowNum);

            $('#dialogElem').dialog('close');

            // Ganti ke halaman yang tepat
            $('#jqGrid').jqGrid('setGridParam', { page: page }).trigger('reloadGrid');

          },
          error: function () {
            Swal.fire({
              title: 'Error!',
              text: 'Gagal Ditambahkan',
              icon: 'error',
            });
          }
        });

      },
      'Cancel': function () {
        $('#dialogElem').dialog('close');
      }
    }

  });


}


function UbahBarang(id) {

  if (id == null) {
    Swal.fire({
      icon: 'question',
      text: 'Pilih data yang mana mau di edit'
    });

    return false;

  }

  $('#dialogElem').load('./app/views/penjualan/form-penjualan.php?id=' + id).dialog({
    modal: true,
    height: 500,
    width: 1100,
    position: { my: "center center", at: "center", of: window },
    title: "Ubah Data",

    buttons: {
      'Ubah Data': function () {
        let sortname = $('#jqGrid').jqGrid('getGridParam', 'sortname');
        let sortorder = $('#jqGrid').jqGrid('getGridParam', 'sortorder');

        // Unformat AutoNumeric sebelum serialize
        $('#data-form .harga, #data-form .qty').each(function () {
          const an = AutoNumeric.getAutoNumericElement(this);
          if (an) this.value = an.getNumber();
        });

        $.ajax({
          url: './app/data/penjualan.php?id=' + id + '&sortname=' + sortname + '&sortorder=' + sortorder + '&action=ubah',
          method: 'POST',
          dataType: 'JSON',
          data: $('#data-form').serialize(),
          success: function (data) {
            $('#dialogElem').dialog('close')
            $('#jqGrid').trigger('reloadGrid')

            // notif saya
            Swal.fire({
              title: 'Berhasil!',
              text: 'Berhasil Diubah',
              icon: 'success',
            });
          },
          error: function () {
            Swal.fire({
              title: 'Error!',
              text: 'Gagal Diubah',
              icon: 'error',
            });
          }
        });
      },
      'Cancel': function () {
        $('#dialogElem').dialog('close');
      }
    }

  });

}

function HapusBarang(id) {

  if (id == null) {
    Swal.fire({
      icon: 'warning',
      text: 'Pilih data yang mana mau di hapus'
    });

    return false;

  }

  $('#dialogElem').load('./app/views/penjualan/form-penjualan.php?id_del=' + id).dialog({
    modal: true,
    height: 500,
    width: 1100,
    position: { my: "center center", at: "center", of: window },
    title: "Hapus Data",

    buttons: {
      'Delete': function () {
        let sortname = $('#jqGrid').jqGrid('getGridParam', 'sortname');
        let sortorder = $('#jqGrid').jqGrid('getGridParam', 'sortorder');

        $.ajax({
          url: './app/data/penjualan.php?id=' + id + '&sortname=' + sortname + '&sortorder=' + sortorder + '&action=hapus',
          method: 'POST',
          dataType: 'JSON',
          data: $('#data-form').serialize(),
          success: function (data) {
            $('#dialogElem').dialog('close')
            $('#jqGrid').trigger('reloadGrid')

            // notif saya
            Swal.fire({
              title: 'Berhasil!',
              text: 'Berhasil Dihapus',
              icon: 'success',
            });
          },
          error: function () {
            Swal.fire({
              title: 'Error!',
              text: 'Gagal Dihapus',
              icon: 'error',
            });
          }
        });
      },
      'Cancel': function () {
        $('#dialogElem').dialog('close');
      }
    }

  });

}

function detailTable(id) {

  const formatOpt = {
    prefix: '',
    thousandsSeparator: ',',
    decimalPlaces: 2,
    decimalSeparator: '.',
  }

  // Detail Table
  jQuery("#detailItem").jqGrid({
    height: 100,
    url: './app/data/detail.php?id=' + id,
    datatype: "json",
    colNames: ['Nama Barang', 'Banyak Barang', 'Harga Satuan (Rp)', 'Total (Rp)'],
    colModel: [
      // { name: 'num', index: 'num', width: 55 },
      { name: 'nama_barang', index: 'nama_barang', width: 180 },
      { name: 'qty', index: 'qty', width: 120, align: "right" },
      { name: 'harga', index: 'harga', width: 120, align: "right", formatter: 'currency', formatoptions: formatOpt },
      {
        name: 'total',
        index: 'total',
        width: 120,
        align: "right",
        sortable: false,
        search: false,
        formatter: 'currency',
        formatoptions: formatOpt,
      },
    ],
    rowNum: 5,
    rowList: [5, 10, 20],
    pager: '#detailItemPager',
    sortname: 'penjualan_id',
    viewrecords: true,
    gridview: true,
    width: 600,
    height: 'auto',
    sortorder: "asc",
    multiselect: false,
    rownumbers: true,
    caption: "Penjualan Detail",
    footerrow: true,
    userDataOnFooter: true,
    gridComplete: function () {

      const arrTotalHarga = $(this).jqGrid('getCol', 'total', false);
      const arrTotalBarang = $(this).jqGrid('getCol', 'qty', false);

      let totalHarga = 0;
      let totalBarang = 0;

      arrTotalHarga.forEach(function (val) {
        // Jika backend kirim angka, cukup parseFloat
        let num = typeof val === 'number' ? val : parseFloat(val);
        if (!isNaN(num)) totalHarga += num;
      });

      arrTotalBarang.forEach(function (val) {
        // Jika backend kirim angka, cukup parseFloat
        let num = typeof val === 'number' ? val : parseFloat(val);
        if (!isNaN(num)) totalBarang += num;
      });
      
      $("#detailItem").jqGrid('footerData', 'set', { nama_barang: 'Total:', total: totalHarga, qty: totalBarang });
    }
  }).navGrid('#detailItemPager', { add: false, edit: false, del: false, search: false, refresh: false });
}

function highlightText(cell, keyword) {
  if (!keyword) return;
  const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const regex = new RegExp(`(${escapedKeyword})`, "gi");
  const updatedHtml = cell.html().replace(regex, '<span class="highlight">$1</span>');
  cell.html(updatedHtml);
}


function higligthPencarian(grid) {
  const postData = grid.getGridParam("postData");
  const filtersJSON = postData.filters;
  const globalSearch = postData.global_search;
  const gridId = $(grid).getGridParam().id;

  // Bersihkan highlight lama
  $(grid).find("td").each(function () {
    const cleanText = $(this).html().replace(/<span class="highlight">(.*?)<\/span>/gi, "$1");
    $(this).html(cleanText);
  });

  let toolbarHasFilter = false;

  // Highlight dari toolbar filter
  if (filtersJSON && typeof filtersJSON === "string") {
    const filterObj = JSON.parse(filtersJSON);
    if (Array.isArray(filterObj.rules)) {
      const filterRules = filterObj.rules;
      toolbarHasFilter = filterRules.some(rule => rule.data && rule.data.trim().length > 0);

      if (toolbarHasFilter) {
        // Reset global search input
        $('#gsearch').val('');
        delete postData.global_search;

        filterRules.forEach(rule => {
          if (rule.data && rule.data.trim().length > 0) {
            const selector = `tbody tr td[aria-describedby="${gridId}_${rule.field}"]`;
            $(grid).find(selector).each(function () {
              highlightText($(this), rule.data);
            });
          }
        });

        return; // stop di sini jika toolbar aktif
      }
    }
  }

  // Jika tidak ada filter toolbar, cek global search
  if (globalSearch && globalSearch.trim().length > 0) {
    $(grid).find("td").each(function () {
      highlightText($(this), globalSearch);
    });
  }
}


let selectId = null;

// Inisialisasi JQGrid
$('#jqGrid').jqGrid({
  url: "./app/data/testing.php",
  mtype: "GET",
  datatype: "json",
  colModel: [
    {
      label: 'Id Bukti',
      name: 'id_penjualan',
      hidden: true,
      key: true,
      width: 75
    },
    {
      label: 'No Bukti',
      name: 'no_bukti',
      width: 150
    },
    {
      label: 'Tanggal Bukti',
      name: 'tgl_bukti',
      width: 150,
      // searchoptions: { 
      //   dataInit: function (el) { 
      //     $(el).datepicker({ dateFormat: 'dd-mm-yy' }); 
      //   } 
      // }
      // formatter: 'date', 
      // formatoptions: { 
      //   srcformat: 'Y-m-d H:i:s', 
      //   newformat: 'ShortDate' 
      // } 
    },
    {
      label: 'Nama Pelanggan',
      name: 'nama_pelanggan',
      width: 150
    }
  ],
  cmTemplate: { required: true },
  width: 1000,
  height: 'auto',
  rowNum: 10,
  mtype: "POST", // ini berpengaruh pada cara pengambilan parameter untuk jqGrid
  rowList: [10, 20, 30],
  rownumbers: true,
  sortname: 'no_bukti',
  viewrecords: true,
  gridview: true,
  // loadonce: true,
  sortorder: "asc",
  caption: "Data Penjualan",
  pager: "#jqGridPager",
  onSelectRow: function (id) {
    jQuery("#detailItem").jqGrid('setGridParam', { url: "./app/data/detail.php?id=" + id, page: 1 });
    jQuery("#detailItem").trigger('reloadGrid');

  },
  loadComplete: function (response) {
    // $("tr.ui-search-toolbar input[name='tgl_bukti']").attr("placeholder", "Cari tgl: 04-06-2025");

    if (selectId == null) {
      const barisPertama = $("#jqGrid").jqGrid('getDataIDs')[0];
      $("#jqGrid").jqGrid('setSelection', barisPertama);
      detailTable(barisPertama);
      
    } else {
      $("#jqGrid").jqGrid('setSelection', selectId);
      detailTable(selectId);

    }

    higligthPencarian($(this));

  },

});

// setting default untuk seluruh action bawaan jqgrid
$("#jqGrid").jqGrid('navGrid', '#jqGridPager', { edit: false, add: false, del: false, search: false, refresh: false });

// navigasi user
$('#jqGrid').jqGrid('bindKeys');

// tombol tambah
$("#jqGrid").jqGrid('navButtonAdd', '#jqGridPager', {
  caption: 'Data Baru',
  buttonicon: 'ui-icon-plus',
  onClickButton: function () {
    tambahBarang();
  },
  position: 'last',
  title: 'Add',
  id: "AddHeader",
  cursor: "pointer",
});

// tombol ubah
$("#jqGrid").jqGrid('navButtonAdd', '#jqGridPager', {
  caption: 'Edit Data',
  buttonicon: 'ui-icon-pencil',
  onClickButton: function () {
    const rowId = $('#jqGrid').jqGrid('getGridParam', 'selrow');
    // console.log(rowId);
    UbahBarang(rowId);
  },
  position: 'last',
  title: 'Edit',
  id: "EditHeader",
  cursor: "pointer",
});

// tombol hapus
$("#jqGrid").jqGrid('navButtonAdd', '#jqGridPager', {
  caption: 'Hapus Data',
  buttonicon: 'ui-icon-trash',
  onClickButton: function () {
    const rowId = $('#jqGrid').jqGrid('getGridParam', 'selrow');
    // console.log(rowId);
    HapusBarang(rowId);
  },
  position: 'last',
  title: 'Hapus',
  id: "HapusHeader",
  cursor: "pointer",
});

// Filter Bar => Untuk mencari data
$('#jqGrid').jqGrid('filterToolbar', {
  autosearch: true,
  stringResult: true,
  searchOnEnter: false,
  defaultSearch: "cn",
  multipleSearch: true,
  beforeSearch: function () {
    const postData = $('#jqGrid').getGridParam("postData");
    delete postData.global_search;
    
    $('#jqGrid').setGridParam({
      search: true,
      postData: {
        _search: true,
      }
    }).trigger('reloadGrid');

  }

});

function resetSearch() {

  $('#gs_no_bukti').val('');
  $('#gs_tgl_bukti').val('');
  $('#gs_nama_pelanggan').val('');

}

// tombol button x 
const buttonX = `<button id="reset_search" type="button" class="active:scale-75" title="Reset All Toolbar Search ">
  <span class="text-2xl text-red-500 font-bold bg-sky-50 px-1 rounded active:bg-sky-600 active:text-slate-50">X</span>
</button>`;
$('#gsh_jqGrid_rn').append(buttonX);
$('#reset_search').click(function () {
  $('#gsearch').val('');
  resetSearch();

  // hapus data pencarian
  const postData = $('#jqGrid').getGridParam("postData");
  delete postData.global_search;
  delete postData.filters;

  // Reset postData dan search = false
  $('#jqGrid').setGridParam({
    search: false,
    postData: {
      _search: false,
    }
  }).trigger('reloadGrid', [{ page: 1 }]);

  // Bersihkan highlight pada semua cell
  $('#jqGrid').find('td').each(function () {
    let html = $(this).html();
    html = html.replace(/<span class="highlight">(.*?)<\/span>/gi, "$1");
    $(this).html(html);
  });

});

// Global Search
const globalSearchElem = `
  <div class='ui-jqgrid-titlebar ui-widget-header'>
    Global Search : 
    <input type='text' name='gsearch' id='gsearch' size='20' class='bg-slate-50 rounded-md outline-none text-slate-700 indent-2'>
  </div>`;
$('.ui-jqgrid-titlebar').after(globalSearchElem);

$('#gsearch').on('keyup', function () {
  let text = $(this).val();

  resetSearch();

  //ada banyak parameter grid, salah satunya postData. untuk nngeliat bisa bikin getGridParam
  //untuk nambahin isi dari parameternya bisa dibuat pake setGridParam
  //jadi untuk search, set dulu data baru untuk param postData. lalu di trigger dengan reloadGrid
  //maka setelah itu, isi param postData bisa bertambah sesuai yg diinginkan
  $('#jqGrid').jqGrid('setGridParam', {
    search: false,
    postData: {
      filters: {},
      _search: false,
      global_search: text
    }
  }).trigger('reloadGrid')

});

// tombol detail
// $("#jqGrid").jqGrid('navButtonAdd', '#jqGridPager', {
//   caption: 'Lihat Data',
//   buttonicon: 'ui-icon-eye',
//   onClickButton: function () {
//     const rowId = $('#jqGrid').jqGrid('getGridParam', 'selrow');
//     // console.log(rowId);
//     // HapusBarang(rowId);
//     LihatBarang(rowId);
//   },
//   position: 'last',
//   title: 'Lihat',
//   id: "LihatHeader",
//   cursor: "pointer",
// });