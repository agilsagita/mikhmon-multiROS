<style>
/* Reset dasar untuk cetak presisi A4 (5x10) */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Container Voucher (Lebar 142px, Tinggi 102px) */
.voucher {
    display: inline-flex;
    flex-direction: column;
    width: 142px; 
    height: 102px; 
    background: #ffffff;
    border: 1.5px solid var(--theme-color, #dc2626); 
    border-radius: 6px; 
    margin: 2px 1px; 
    font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif; 
    color: #000000;
    overflow: hidden; 
    vertical-align: top;
    
    /* Variabel default warna tema */
    --theme-color: #dc2626; 
}

.voucher.dynamic-colored {
    border-color: var(--theme-color) !important;
}

/* 1. HEADER ATAS: LOGO & PROFILE TAG */
.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2px 4px;
    background: #ffffff; 
    border-bottom: 1px solid #f1f5f9; 
}

.logo {
    height: 9px; 
    object-fit: contain;
}

.meta-profile {
    font-size: 6.5px;
    font-weight: 900;
    color: #0f172a;
    text-transform: uppercase;
}

/* 2. KODE VOUCHER BLOK SOLID (DI BAWAH LOGO, DI ATAS HARGA) */
.code-header-wrapper {
    background-color: var(--theme-color) !important;
    padding: 3px 4px;
    text-align: center;
}

.code-label {
    font-size: 5.5px;
    font-weight: 700;
    color: #ffffff;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 1px;
}

.code-value {
    font-size: 14px; /* Ukuran Kode Jumbo */
    font-weight: 900; 
    color: #ffffff !important; 
    letter-spacing: 0.5px;
    text-transform: none; /* Aman login case-sensitive */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 1;
}

/* Mode UP (Username & Password) Dalam Blok */
.up-row {
    display: flex;
    justify-content: space-around;
    align-items: center;
}
.up-box {
    text-align: center;
}
.up-label {
    font-size: 5px;
    color: #ffffff;
    opacity: 0.9;
    text-transform: uppercase;
}
.up-value {
    font-size: 10px;
    font-weight: 900;
    color: #ffffff !important;
    line-height: 1;
}

/* 3. AREA TENGAH: HARGA & DETAIL PAKET */
.content {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 3px 5px;
    flex-grow: 1;
}

.price-container {
    text-align: center;
    margin: 1px 0;
}

.price {
    font-size: 14px; /* Harga Jumbo */
    font-weight: 900; 
    color: var(--theme-color) !important;
    line-height: 1;
}

.details-row {
    display: flex;
    justify-content: space-between;
    font-size: 6px;
    font-weight: 700;
    color: #334155;
    border-top: 1px dotted #cbd5e1;
    padding-top: 2px;
}

/* 4. FOOTER CATATAN */
.footer {
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    color: #64748b;
    padding: 1px 2px;
    font-size: 5.5px;
    font-weight: 600;
    text-align: center;
    line-height: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<!-- Attribut data-price untuk pemetaan warna dinamis -->
<div class="voucher js-price-voucher" data-price="<?= $price; ?>">

    <!-- 1. Logo Paling Atas -->
    <div class="top-header">
        <img src="<?= $logo; ?>" class="logo" alt="Logo">
        <div class="meta-profile"><?= $profile; ?></div>
    </div>

    <!-- 2. Blok Kode Voucher Di Bawah Logo -->
    <div class="code-header-wrapper">
        <?php if($usermode=="vc"){ ?>
            <div class="code-label">Kode Voucher</div>
            <div class="code-value"><?= $username; ?></div>
        <?php } ?>

        <?php if($usermode=="up"){ ?>
            <div class="up-row">
                <div class="up-box">
                    <div class="up-label">USER</div>
                    <div class="up-value"><?= $username; ?></div>
                </div>
                <div class="up-box">
                    <div class="up-label">PASS</div>
                    <div class="up-value"><?= $password; ?></div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- 3. Harga Jumbo & Detail Paket di Bawah Kode -->
    <div class="content">
        
        <div class="price-container">
            <div class="price"><?= $price; ?></div>
        </div>

        <div class="details-row">
            <span>Masa Aktif: <b><?= $validity; ?></b></span>
            <?php if($timelimit){ ?><span>Waktu: <b><?= $timelimit; ?></b></span><?php } ?>
        </div>

    </div>

    <!-- 4. Footer Catatan -->
    <div class="footer">
        Jangan buang dulu sebelum habis.
    </div>

</div>

<!-- Script Pemetaan Warna Dinamis Sesuai Paket Anda -->
<script>
(function() {
    var priceColorMap = {
        '1000':  '#2563eb', // Biru Terang (5JAM)
        '2000':  '#7c2d12', // Cokelat Tua (12JAM)
        '3000':  '#dc2626', // Merah Terang (24JAM)
        '20000': '#be185d', // Pink / Magenta Tua (1-MINGGU)
        '70000': '#4338ca'  // Indigo / Biru Gelap (1-BULAN)
    };

    var vouchers = document.querySelectorAll('.js-price-voucher:not(.dynamic-colored)');
    vouchers.forEach(function(el) {
        var rawPrice = el.getAttribute('data-price') || '';
        var cleanPrice = rawPrice.replace(/[^0-9]/g, '');
        
        var selectedColor = priceColorMap[cleanPrice];
        
        if (!selectedColor) {
            var hash = 0;
            for (var i = 0; i < cleanPrice.length; i++) {
                hash = cleanPrice.charCodeAt(i) + ((hash << 5) - hash);
            }
            var hue = Math.abs(hash) % 360;
            selectedColor = 'hsl(' + hue + ', 85%, 35%)';
        }
        
        el.style.setProperty('--theme-color', selectedColor);
        el.classList.add('dynamic-colored');
    });
})();
</script>