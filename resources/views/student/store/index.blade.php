@extends('layouts.student')

@section('title', 'Tienda — Layra Fit')

@section('content')
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">
        <i class="fa-solid fa-bag-shopping" style="color: var(--accent); margin-right: 8px;"></i> Tienda LayraFit
    </h2>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Adquiere productos oficiales, accesorios y suplementos. Pago rápido y seguro vía Yape.</p>
</div>

@php
    $products = [
        ['name' => 'Polo Oficial LayraFit', 'category' => 'Ropa', 'price' => 35.00, 'icon' => 'fa-shirt', 'color' => '#ef4444', 'desc' => 'Polo deportivo transpirable.'],
        ['name' => 'Sudadera Unisex LayraFit', 'category' => 'Ropa', 'price' => 65.00, 'icon' => 'fa-vest', 'color' => '#c084fc', 'desc' => 'Comodidad y estilo para tus rutinas.'],
        ['name' => 'Short Deportivo', 'category' => 'Ropa', 'price' => 45.00, 'icon' => 'fa-running', 'color' => '#10b981', 'desc' => 'Ideal para máxima movilidad.'],
        ['name' => 'Toma Todo 1L', 'category' => 'Accesorios', 'price' => 25.00, 'icon' => 'fa-bottle-water', 'color' => '#3b82f6', 'desc' => 'Mantente hidratada en cada clase.'],
        ['name' => 'Pesas / Mancuernas (Par 2kg)', 'category' => 'Equipamiento', 'price' => 40.00, 'icon' => 'fa-dumbbell', 'color' => '#f59e0b', 'desc' => 'Para rutinas de Strong y Funcional.'],
        ['name' => 'Zapatillas Deportivas', 'category' => 'Calzado', 'price' => 120.00, 'icon' => 'fa-shoe-prints', 'color' => '#ec4899', 'desc' => 'Amortiguación ideal para Zumba.'],
        ['name' => 'Proteína Whey 1kg', 'category' => 'Nutrición', 'price' => 150.00, 'icon' => 'fa-jar', 'color' => '#8b5cf6', 'desc' => 'Recuperación muscular post-entrenamiento.'],
    ];
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px;">
    @foreach($products as $product)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
            
            <div style="height: 120px; background: linear-gradient(135deg, rgba(20,20,25,1), rgba(40,30,50,1)); display: flex; align-items: center; justify-content: center; position: relative;">
                <div style="position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.1); font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; color: #fff;">{{ $product['category'] }}</div>
                <i class="fa-solid {{ $product['icon'] }}" style="font-size: 50px; color: {{ $product['color'] }}; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));"></i>
            </div>
            
            <div style="padding: 14px; flex: 1; display: flex; flex-direction: column;">
                <h4 style="font-size: 14px; font-weight: 800; color: #fff; margin-bottom: 4px; line-height: 1.2;">{{ $product['name'] }}</h4>
                <p style="font-size: 11px; color: var(--text-muted); line-height: 1.3; margin-bottom: 12px; flex: 1;">{{ $product['desc'] }}</p>
                
                <div style="font-size: 18px; font-weight: 900; color: var(--accent); margin-bottom: 12px;">
                    S/ {{ number_format($product['price'], 2) }}
                </div>
                
                <button type="button" onclick="buyWithYape('{{ $product['name'] }}', {{ $product['price'] }})" class="btn btn-sm" style="background: #742284; color: #fff; border: none; font-weight: 800; display: flex; justify-content: center; gap: 6px; width: 100%;">
                    <i class="fa-solid fa-qrcode"></i> Pagar con Yape
                </button>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Yape -->
<div id="yape-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; backdrop-filter: blur(5px); justify-content: center; align-items: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--primary); border-radius: 20px; width: 100%; max-width: 360px; overflow: hidden; position: relative;">
        <button type="button" onclick="document.getElementById('yape-modal').style.display = 'none'" style="position: absolute; top: 12px; right: 12px; background: transparent; border: none; color: #fff; font-size: 20px; cursor: pointer;"><i class="fa-solid fa-times"></i></button>
        
        <div style="background: #742284; padding: 20px; text-align: center;">
            <i class="fa-solid fa-bag-shopping" style="font-size: 30px; color: #fff; margin-bottom: 10px;"></i>
            <h3 style="color: #fff; font-size: 18px; font-weight: 800; margin: 0;">Compra Segura</h3>
        </div>
        
        <div style="padding: 24px; text-align: center;">
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 6px;">Producto:</div>
            <div id="yape-product-name" style="font-size: 16px; font-weight: 800; color: #fff; margin-bottom: 16px;">--</div>
            
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 6px;">Total a pagar:</div>
            <div id="yape-product-price" style="font-size: 28px; font-weight: 900; color: var(--accent); margin-bottom: 24px;">S/ 0.00</div>
            
            <div style="background: rgba(255,255,255,0.05); padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                <img src="{{ asset('images/yape-qr.png') }}" alt="QR Yape" style="width: 150px; height: 150px; border-radius: 12px; margin-bottom: 12px; background: #fff; padding: 4px;" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg'">
                <div style="font-size: 15px; font-weight: 700; color: #fff;">Layra Fit SRL</div>
                <div style="font-size: 18px; font-weight: 900; color: var(--accent); margin-top: 4px;">999 888 777</div>
            </div>
            
            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">1. Escanea el QR o envía el pago al número.<br>2. Envía la captura al WhatsApp para coordinar la entrega en tu sede.</p>
            
            <a id="yape-whatsapp-btn" href="#" target="_blank" class="btn btn-primary" style="width: 100%; display: flex; justify-content: center; gap: 8px; font-weight: 700; background: #25D366; border-color: #25D366;">
                <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i> Enviar Captura
            </a>
        </div>
    </div>
</div>

<script>
    function buyWithYape(name, price) {
        document.getElementById('yape-product-name').innerText = name;
        document.getElementById('yape-product-price').innerText = 'S/ ' + price.toFixed(2);
        
        let text = encodeURIComponent('Hola LayraFit, acabo de pagar por Yape el monto de S/ ' + price.toFixed(2) + ' por el producto: ' + name + '. Adjunto captura.');
        document.getElementById('yape-whatsapp-btn').href = 'https://wa.me/51999888777?text=' + text;
        
        document.getElementById('yape-modal').style.display = 'flex';
    }
</script>
@endsection