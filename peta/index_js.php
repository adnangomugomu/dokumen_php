<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
    // Init map
    var map;
    const LAYER = {};
    map = L.map(`map-id`, {
        doubleClickZoom: false,
        zoomControl: false,
    }).setView([-7.55438628697766, 110.82560387982298], 11);

    setInterval(function() {
        map.invalidateSize();
    }, 1000);

    // Base map
    let basemap = {
        "osm": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }),
        "Google Roadmap": L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; <a href="https://google.com/maps/">Google Maps</a>'
        }),
        "Google Satellite": L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; <a href="https://google.com/maps/">Google Maps</a>'
        }).addTo(map),
        "Google Hybrid": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; <a href="https://google.com/maps/">Google Maps</a>'
        }),
        "Google Terrain": L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; <a href="https://google.com/maps/">Google Maps</a>'
        }),
        "Esri World Imagery": L.esri.tiledMapLayer({
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer',
            maxZoom: 17,
        }),
        "Esri World Street Map": L.esri.tiledMapLayer({
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer',
            maxZoom: 17,
        }),
        "Esri World Topo Map": L.esri.tiledMapLayer({
            url: 'https://services.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer',
            maxZoom: 17,
        }),
        "Peta RBI": L.esri.tiledMapLayer({
            url: 'https://portal.ina-sdi.or.id/arcgis/rest/services/RBI/Basemap/MapServer',
            maxZoom: 19,
        })
    }

    L.control.layers(basemap, null, {
        position: 'bottomleft'
    }).addTo(map);

    L.control.scale({
        position: 'bottomright',
    }).addTo(map);

    // Zoom In & Out Event
    L.DomEvent.on(L.DomUtil.get('zoom-in'), 'click', (event) => map.setZoom(map.getZoom() + 1))
    L.DomEvent.on(L.DomUtil.get('zoom-out'), 'click', (event) => map.setZoom(map.getZoom() - 1));
    L.DomEvent.on(L.DomUtil.get('center'), 'click', (event) => map.setView([-7.55438628697766, 110.82560387982298], 14));
    $('#layer').on('click', function(e) {
        $('#detail').hide();
        if ($('#sidebar_layer').is(':visible')) {
            $('#sidebar_layer').slideUp(400);
        } else {
            $('.popup_data').hide();
            $('#sidebar_layer').slideDown(400);
        }
    });
</script>

<script>
    $(document).ready(function() {
        generate_group();
    });

    var geoJsonBatasKecamatan = new L.GeoJSON.AJAX("<?= base_url('peta/geo_batas_kecamatan') ?>", {
        style: function(f) {
            let color;
            switch (f.properties.nama) {
                case 'LAWEYAN':
                    color = '#ff3838';
                    break;
                case 'SERENGAN':
                    color = '#7158e2';
                    break;
                case 'PASAR KLIWON':
                    color = '#fff200';
                    break;
                case 'JEBRES':
                    color = '#3ae374';
                    break;
                case 'BANJARSARI':
                    color = '#17c0eb';
                    break;
                default:
                    color = '#ff9f1a';
            }

            return {
                color: color,
                // fillColor: '#27ae60',
                opacity: .9,
                weight: 2,
                dashArray: '3,5',
                dahsOffset: 0
            }
        },
        onEachFeature: function(feature, layer) {
            layer.on({
                'click': function(e) {
                    var data = e.target.feature.properties;
                    layer.bindPopup(`
                            <h5>${data.nama}</h5>
                        `);
                }
            });
        }
    });

    geoJsonBatasKecamatan.on("data:loading", function() {});

    geoJsonBatasKecamatan.on("data:loaded", function() {
        setTimeout(() => {
            map.fitBounds(geoJsonBatasKecamatan.getBounds());
        }, 1000);
        geoJsonBatasKecamatan.addTo(map);
    });

    function loop_group(data) {
        var html = '';

        $.map(data, function(e, i) {

            var total = e.total_downline;

            if (total == 0) {

                html += `
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-body p-1">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" data-id="${e.id}" class="custom-control-input cek_layer" id="id_${e.parent + '_' + e.id}">
                            <label class="custom-control-label" for="id_${e.parent + '_' + e.id}"> <i class="fa fa-map-marker-alt"></i> ${e.nama}</label>
                        </div>
                    </div>
                </div>
                `;

            } else {

                html += `
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e3e3e3;">
                        <a class="collapsed text-dark" data-toggle="collapse" href="#${'id_'+e.parent + '_' + e.id}" aria-expanded="false">
                            ${e.nama}
                        </a>
                    </div>
                    <div id="${'id_'+e.parent + '_' + e.id}" class="collapse">
                        <div class="card-body p-1">
                            `;

                html += loop_group(e.child);

                html += `

                        </div>
                    </div>
                </div>
                `;
            }
        });

        return html;
    }

    function generate_group() {

        $.ajax({
            url: '<?= base_url('peta/generate_group') ?>',
            type: "GET",
            dataType: 'JSON',
            processData: false,
            contentType: false,
            beforeSend: function() {
                // console.log('sedang menghapus');
            },
            complete: function() {
                // console.log('Berhasil');
            },
            error: function(e) {
                console.log(e);
                toastr.error('gagal, terjadi kesalahan');
            },
            success: function(res) {
                if (res.status == 'success') {

                    var list = loop_group(res.data);

                    list += `
                        <div class="card mt-2" style="margin-bottom: 10px;">
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" onchange="load_layer_kelurahan(this,'kriteria_xxxx')" id="kriteria_xxxx">
                                            <label class="custom-control-label" for="kriteria_xxxx"> <i class="fa fa-map-marker-alt"></i> Batas Kelurahan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <i style="display: none;" class="loader_kriteria_xxxx fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#info_dasar').html(list);

                } else {
                    toastr.error(res.msg);
                }
            },
        });
    }

    function select_layer(layer, e) {

        var data = e.target.feature.properties;
        var is_visible_info = $("#detail_klik").is(":visible");
        if (!is_visible_info) $('#detail_klik').fadeIn(500);

        var data_show = '';
        var kondisi = [
            'objectid', 'shape_length', 'stroke', 'shape_area', 'fill', 'fill_opacity', 'stroke_opacity', 'icon_name',
            'orde 1', 'orde 2', 'orde 3', 'orde 4', 'namobj'
        ];

        $.map(data, function(e, i) {
            if (!kondisi.includes(i.toLowerCase())) {
                data_show += `
                <tr>
                    <td>${i}</td>
                    <td>${e}</td>
                </tr>
                `;
            }
        });

        var html = `
        <table style="width: 100%;" class="table table-hover">
            <tbody>
                ${data_show}
            </tbody>
        </table>
        `;

        $('#detail_html').html(html);
        $('#sidebar_layer').hide();
    }

    let setting_style = (f) => {
        var obj = f.properties;

        var color = '#f24141'
        var fill_opacity = 0.3;

        if (obj.stroke != undefined) color = obj.stroke;
        if (obj.color != undefined) color = obj.color;
        if (obj.fill != undefined) color = obj.fill;
        if (obj.fill_opacity != undefined) fill_opacity = obj.fill_opacity;

        return {
            fillColor: color,
            fillOpacity: fill_opacity,
            color: color,
            opacity: .7,
            weight: 2,
            dashArray: 0,
            dahsOffset: 0
        }
    }

    function load_layer(file, name) {

        var layer = new L.GeoJSON.AJAX('<?= base_url('uploads/file/') ?>' + file, {
            style: setting_style,
            onEachFeature: function(feature, layer) {
                layer.on({
                    'click': function(e) {
                        select_layer(e.target, e);
                    }
                });
            }
        }).addTo(map);

        layer.on("data:loading", function() {
            // loading_show();
        });

        layer.on("data:loaded", function() {
            // loading_hide();
            // setTimeout(() => {
            // map.fitBounds(layer.getBounds());
            // }, 500);
            LAYER[name] = layer;
        });
    }

    $('body').on('change', '.cek_layer', function() {

        var checkbox = $(this);
        var id = $(this).data('id');
        var data = new FormData();
        data.append('id', id)

        $.ajax({
            url: '<?= base_url('peta/get_layer') ?>',
            type: "POST",
            data: data,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            error: function(e) {
                console.log(e);
                toastr.error('gagal, terjadi kesalahan');
            },
            success: function(res) {
                if (res.status == 'success') {

                    var data = res.data;
                    if ($(checkbox).is(':checked')) {

                        var kondisi = [null, '', '-'];
                        if (!kondisi.includes(data.file)) {
                            if (LAYER[data.nama]) {
                                LAYER[data.nama].addTo(map);
                            } else {
                                load_layer(data.file, data.nama);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Perhatian',
                                text: 'file tidak ditemukan',
                                timer: 2000,
                                showConfirmButton: false,
                            })
                        }
                    } else {
                        if (LAYER[data.nama]) map.removeLayer(LAYER[data.nama]);
                    }
                } else {
                    toastr.error(res.msg);
                }
            },
        });

    });

    const LAYER_KHUSUS = {};

    function load_layer_dasar(dt, id, kode) {
        if ($(dt).is(':checked')) {
            if (LAYER_KHUSUS[kode]) {
                map.addLayer(LAYER_KHUSUS[kode]);

                setTimeout(() => {
                    map.fitBounds(LAYER_KHUSUS[kode].getBounds());
                }, 1000);

            } else {
                $.ajax({
                    type: "GET",
                    url: "<?= base_url('peta/load_geo/') ?>" + id,
                    data: {},
                    dataType: "JSON",
                    beforeSend: function(res) {
                        $('.loader_' + kode).show();
                    },
                    complete: function(res) {
                        $('.loader_' + kode).hide();
                    },
                    success: function(res) {

                        LAYER_KHUSUS[kode] = L.markerClusterGroup({
                            disableClusteringAtZoom: 19,
                            zoomToBoundsOnClick: true,
                        });

                        $.map(res.features, function(e, i) {

                            var row = e.properties;
                            var m = L.marker([row.lat, row.long]);
                            m.bindPopup(load_popup(row));
                            LAYER_KHUSUS[kode].addLayer(m);
                        });

                        map.addLayer(LAYER_KHUSUS[kode]);

                        setTimeout(() => {
                            map.fitBounds(LAYER_KHUSUS[kode].getBounds());
                        }, 1000);
                    }
                });
            }
        } else {
            map.removeLayer(LAYER_KHUSUS[kode]);
        }
    }

    function load_layer_kriteria(dt, id, kode) {
        if ($(dt).is(':checked')) {
            if (LAYER_KHUSUS[kode]) {
                map.addLayer(LAYER_KHUSUS[kode]);

                setTimeout(() => {
                    map.fitBounds(LAYER_KHUSUS[kode].getBounds());
                }, 1000);

            } else {
                $.ajax({
                    type: "GET",
                    url: "<?= base_url('peta/load_geo_kriteria/') ?>" + id,
                    data: {},
                    dataType: "JSON",
                    beforeSend: function(res) {
                        $('.loader_' + kode).show();
                    },
                    complete: function(res) {
                        $('.loader_' + kode).hide();
                    },
                    success: function(res) {

                        LAYER_KHUSUS[kode] = L.markerClusterGroup({
                            disableClusteringAtZoom: 19,
                            zoomToBoundsOnClick: true,
                        });

                        $.map(res.features, function(e, i) {

                            var row = e.properties;
                            var m = L.marker([row.lat, row.long]);
                            m.bindPopup(load_popup(row));
                            LAYER_KHUSUS[kode].addLayer(m);
                        });

                        map.addLayer(LAYER_KHUSUS[kode]);

                        setTimeout(() => {
                            map.fitBounds(LAYER_KHUSUS[kode].getBounds());
                        }, 1000);
                    }
                });
            }
        } else {
            map.removeLayer(LAYER_KHUSUS[kode]);
        }
    }

    function load_popup(row) {
        var tr = '';

        $.map(row, function(e, i) {

            if (!['id', 'lat', 'long'].includes(i)) {
                tr += `
                    <tr>
                        <td>${i.replaceAll('_',' ')}</td>
                        <td>${e??'-'}</td>
                    </tr>
                `;
            }
        });

        var html = `
            <div style="height:200px;overflow-y:auto;">
                <table class="table table-bordered table-sm table-striped table_target table_1">
                    ${tr}
                </table>
                <table class="table table-bordered table-sm table-striped table_target table_2" style="display:none;">
                    <tr>
                        <td>Lat</td>
                        <td>${row.lat}</td>
                    </tr>
                    <tr>
                        <td>Long</td>
                        <td>${row.long}</td>
                    </tr>
                </table>
            </div>
            <button onclick="set_radio(this,'table_1')" class="btn btn_pilih btn-sm btn-outline-primary active">Properti</button>
            <button onclick="set_radio(this,'table_2')" class="btn btn_pilih btn-sm btn-outline-primary">Info</button>
        `;

        return html;
    }

    function set_radio(dt, target) {
        $('.btn_pilih').removeClass('active');
        $(dt).addClass('active');

        $('.table_target').hide()
        $('.' + target).show()

    }

    function set_radio_2(dt, target) {
        $('.btn_select').removeClass('active');
        $(dt).addClass('active');

        $('.tampil_ganti').hide()
        $('#' + target).show()

    }

    var geojson_kelurahan;

    function load_layer_kelurahan(dt, id) {
        if ($(dt).is(':checked')) {
            if (!geojson_kelurahan) {
                console.log('oke');
                geojson_kelurahan = new L.GeoJSON.AJAX("<?= base_url('peta/load_layer_kelurahan') ?>", {
                    style: function(f) {
                        let color = f.properties.color;

                        return {
                            fillColor: color,
                            fillOpacity: 0.3,
                            color: color,
                            opacity: 0.5,
                            weight: 1,
                            dashArray: '3,5',
                            dahsOffset: 0
                        }
                    },
                    onEachFeature: function(feature, layer) {
                        layer.on({
                            'click': function(e) {
                                var data = e.target.feature.properties;
                                layer.bindPopup(`
                                    <h5>Kelurahan : ${data.nama}</h5>
                                `);
                            }
                        });
                    }
                }).addTo(map);

                geojson_kelurahan.on("data:loading", function() {
                    $('.loader_' + id).show();
                });
                geojson_kelurahan.on("data:loaded", function() {
                    $('.loader_' + id).hide();
                });
            } else {
                geojson_kelurahan.addTo(map);
            }
        } else {
            if (geojson_kelurahan) {
                map.removeLayer(geojson_kelurahan);
            }
        }
    }
</script>