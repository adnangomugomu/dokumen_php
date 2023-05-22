<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <!-- Website description -->
    <meta name='description' content='rumah rawan bencana pemerintah kota surakarta' />
    <!-- Author Name -->
    <meta name='author' content='disperkimtan surakarta' />
    <!-- SEO keyword -->
    <meta name='keywords' content='rumah rawan bencana pemerintah kota surakarta'>
    <!-- Robots Meta Tag -->
    <meta name='robots' content='index, follow'>

    <!-- favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/skote/images/logo-ska.png') ?>">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Icons -->
    <link href="<?= base_url('assets/peta/') ?>css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Slider -->
    <link rel="stylesheet" href="<?= base_url('assets/peta/') ?>css/tiny-slider.css" />
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Leaflet Fullscreen -->
    <link href="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css" rel='stylesheet' />
    <!-- Main Css -->
    <link href="<?= base_url('assets/peta/') ?>css/style.min.css" rel="stylesheet" type="text/css" id="theme-opt" />
    <link href="<?= base_url('assets/peta/') ?>css/colors/default.css" rel="stylesheet" id="color-opt">
    <link href="<?= base_url('assets/peta/') ?>css/custom-kab.css" rel="stylesheet" id="custom-css">

    <title>Rumah Rawan Bencana</title>

    <style>
        #sidebar_sektoral,
        #sidebar_layer,
        #detail_klik {
            position: absolute;
            right: 20px;
            top: 100px;
            width: 500px;
            max-width: 500px;
            background-color: rgba(196, 47, 28, 0.85);
            z-index: 9999;
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 10px;
            padding-top: 20px;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 5px !important;
        }
    </style>
</head>

<body>
    <header id="topnav" class="defaultscroll sticky map-header">
        <div class="container-fluid">
            <!-- Logo container-->
            <a style="position: absolute;" class="logo logo-wrap" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/skote/images/logo-ska.png') ?>" height="120" class="logo-light-mode" alt="">
            </a>

            <div class="buy-button">
                <div class="list-navigation">
                    <a class="nav-btn" href="<?= base_url() ?>" title="Menuju ke Beranda"><i data-feather="home" class="fea-lg"></i></a>
                    <a class="nav-btn" id="layer" href="javascript:void(0);"><i data-feather="layers" class="fea-lg"></i></a>
                    <a class="nav-btn" id="center" href="javascript:void(0);"><i data-feather="map-pin" class="fea-lg"></i></a>
                    <a class="nav-btn" id="zoom-out" href="javascript:void(0);"><i data-feather="zoom-out" class="fea-lg"></i></a>
                    <a class="nav-btn" id="zoom-in" href="javascript:void(0);"><i data-feather="zoom-in" class="fea-lg"></i></a>
                    <div class="form-icon position-relative" hidden>
                        <input type="text" name="search" id="search" class="form-control ps-5 rounded" placeholder="Cari dengan Kata Kunci : " required>
                        <button class="btn search-btn" type="submit">
                            <i data-feather="corner-down-right" class="btn-icon fea icon-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- End Logo container-->
            <div class="menu-extras d-none">
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>
        </div>
    </header>

    <section class="peta-section p-0">
        <div id="map-id"></div>
        <div class="peta-wrap mb-3 mb-lg-4">

            <div id="sidebar_layer" class="rounded popup_data" style="display:none;padding-top: 5px !important;">
                <h3 class="text-center text-white font-weight-bold">LAYER</h3>
                <button class="btn btn-light btn-sm btn-rounded" onclick="$('#sidebar_layer').slideUp(400);" style="position: absolute; top: 10px;right: 10px;">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <div class="p-3 rounded" id="layer_all" style="background-color: #fff;max-height: 450px;overflow-y: auto;">
                    <div class="mb-2">
                        <button onclick="set_radio_2(this,'info_kelurahan')" class="btn_select btn btn-outline-primary btn-sm active">Kelurahan</button>
                        <button onclick="set_radio_2(this,'info_dasar')" class="btn_select btn btn-outline-primary btn-sm">Informasi Dasar</button>
                        <button onclick="set_radio_2(this,'info_kriteria')" class="btn_select btn btn-outline-primary btn-sm">Kriteria</button>
                    </div>

                    <div class="tampil_ganti" id="info_kelurahan">
                        <?php foreach ($mst_kelurahan as $i => $key) :
                            $i = 'jenis_' . $i;
                        ?>
                            <div class="card mt-2" style="margin-bottom: 10px;">
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" onchange="load_layer_dasar(this,'<?= $key->id ?>','<?= $i ?>')" id="id_kec_<?= $i ?>">
                                                <label class="custom-control-label" for="id_kec_<?= $i ?>"> <i class="fa fa-map-marker-alt"></i>Kelurahan <?= $key->nama ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <i style="display: none;" class="loader_<?= $i ?> fa fa-spinner fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="tampil_ganti" style="display: none;" id="info_dasar"></div>

                    <div class="tampil_ganti" style="display: none;" id="info_kriteria">
                        <?php foreach ($ref_sub_kriteria as $i => $key) :
                            $i = 'kriteria_' . $i;
                        ?>
                            <div class="card mt-2" style="margin-bottom: 10px;">
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" onchange="load_layer_kriteria(this,'<?= $key->id ?>','kriteria_<?= $i ?>')" id="kriteria_<?= $i ?>">
                                                <label class="custom-control-label" for="kriteria_<?= $i ?>"> <i class="fa fa-map-marker-alt"></i> <?= $key->nama ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <i style="display: none;" class="loader_kriteria_<?= $i ?> fa fa-spinner fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>

            <div id="detail_klik" class="rounded popup_data" style="display:none;">
                <h3 class="text-center text-white font-weight-bold">INFORMASI</h3>
                <button class="btn btn-light btn-sm btn-rounded" onclick="$('#detail_klik').slideUp(400);" style="position: absolute; top: 10px;right: 10px;">
                    <i class="fa fa-times"></i> Tutup
                </button>
                <div class="p-3 rounded" id="detail_html" style="background-color: #fff;height: 250px;overflow-y: auto;">

                </div>
            </div>

            <div class="mobile-nav-wrap">
                <div class="mobile-nav">
                    <a class="nav-btn" href="<?= base_url() ?>" title="Menuju ke Beranda"><i data-feather="home" class="fea-lg"></i></a>
                    <a class="nav-btn" id="layer" href="javascript:void(0);"><i data-feather="layers" class="fea-lg"></i></a>
                    <a class="nav-btn" id="center" href="javascript:void(0);"><i data-feather="map-pin" class="fea-lg"></i></a>
                    <a class="nav-btn" id="zoom-out" href="javascript:void(0);"><i data-feather="zoom-out" class="fea-lg"></i></a>
                    <a class="nav-btn" id="zoom-in" href="javascript:void(0);"><i data-feather="zoom-in" class="fea-lg"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- JS Scripts -->
    <script src="<?= base_url('assets/peta/') ?>js/jquery.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/jquery.fancybox.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/tilt.jquery.min.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/jquery.paroller.min.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/peta/') ?>js/tiny-slider.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/feather.min.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/switcher.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/plugins.init.js"></script>
    <script src="<?= base_url('assets/peta/') ?>js/app.js"></script>

    <!-- Loading Overlay -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <!-- Axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- Leaflet.js -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <!-- Leaflet AJAX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js" integrity="sha512-Abr21JO2YqcJ03XGZRPuZSWKBhJpUAR6+2wH5zBeO4wAw4oksr8PRdF+BKIRsxvCdq+Mv4670rZ+dLnIyabbGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Leaflet Fullscreen -->
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

    <!-- Esri Leaflet -->
    <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js" integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g==" crossorigin=""></script>

    <!-- Esri Leaflet Vector -->
    <script src="https://unpkg.com/esri-leaflet-vector@3.1.1/dist/esri-leaflet-vector.js" integrity="sha512-7rLAors9em7cR3/583gZSvu1mxwPBUjWjdFJ000pc4Wpu+fq84lXF1l4dbG4ShiPQ4pSBUTb4e9xaO6xtMZIlA==" crossorigin=""></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.all.min.js"></script>
    <?php include('index_js.php'); ?>
</body>

</html>