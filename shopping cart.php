<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$products = [
    1 => ['name'=>'Wireless Headphones', 'price'=>59.99, 'image'=>'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=300'],
    2 => ['name'=>'Smart Watch', 'price'=>129.99, 'image'=>'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=300'],
    3 => ['name'=>'Running Shoes', 'price'=>89.99, 'image'=>'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=300'],
    4 => ['name'=>'Leather Backpack', 'price'=>49.99, 'image'=>'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=300']
];

if (isset($_GET['action'])) {
    $id = (int)($_GET['id'] ?? 0);
    if ($_GET['action'] == 'add' && isset($products[$id])) {
        $_SESSION['cart'][$id] = $_SESSION['cart'][$id] ?? $products[$id];
        $_SESSION['cart'][$id]['quantity'] = ($_SESSION['cart'][$id]['quantity'] ?? 0) + 1;
    }
    elseif ($_GET['action'] == 'remove' && isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    elseif (($_GET['action'] == 'checkout')) { echo "<script>alert('Order placed!');</script>"; $_SESSION['cart'] = []; }
}

$subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart']));
$total = $subtotal + 5.99;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;font-family:Arial,sans-serif}
        body{background:#f5f5f5;padding:20px}
        .container{max-width:1200px;margin:0 auto}
        header{background:#4a6baf;color:white;padding:20px;text-align:center;margin-bottom:20px;border-radius:8px}
        .cart-icon{position:fixed;top:20px;right:20px;background:#ff6b6b;color:white;width:50px;height:50px;border-radius:50%;display:grid;place-items:center;cursor:pointer;z-index:100}
        .cart-count{position:absolute;top:-5px;right:-5px;background:#4a6baf;color:white;border-radius:50%;width:20px;height:20px;font-size:12px;display:grid;place-items:center}
        .products{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:15px;margin-bottom:30px}
        .product{background:white;border-radius:8px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.1)}
        .product-image{height:180px;overflow:hidden}
        .product-image img{width:100%;height:100%;object-fit:cover}
        .product-info{padding:15px}
        .product-name{font-weight:600;margin-bottom:8px}
        .product-price{color:#4a6baf;font-weight:bold;margin-bottom:10px}
        .add-btn{width:100%;padding:8px;background:#4a6baf;color:white;border:none;border-radius:4px;cursor:pointer}
        .cart-container{background:white;border-radius:8px;padding:20px;margin-bottom:20px}
        table{width:100%;border-collapse:collapse;margin:10px 0}
        th,td{padding:10px;text-align:left;border-bottom:1px solid #eee}
        .cart-item-image{width:60px;height:60px;border-radius:4px;overflow:hidden}
        .cart-item-image img{width:100%;height:100%;object-fit:cover}
        .remove-btn{background:#ff6b6b;color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer}
        .summary{background:white;border-radius:8px;padding:20px}
        .summary-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee}
        .summary-row.total{font-weight:bold;color:#4a6baf;border:none}
        .checkout-btn{width:100%;padding:10px;background:#48bb78;color:white;border:none;border-radius:4px;cursor:pointer;margin-top:15px}
        .empty-cart{text-align:center;padding:30px 0;color:#777}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Simple Shop</h1>
        </header>
        
        <div class="cart-icon" onclick="scrollToCart()">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?=count($_SESSION['cart'])?></span>
        </div>
        
        <h2>Products</h2>
        <div class="products">
            <?php foreach($products as $id=>$product): ?>
            <div class="product">
                <div class="product-image">
                    <img src="<?=$product['image']?>" alt="<?=$product['name']?>">
                </div>
                <div class="product-info">
                    <div class="product-name"><?=$product['name']?></div>
                    <div class="product-price">$<?=number_format($product['price'],2)?></div>
                    <button class="add-btn" onclick="addToCart(<?=$id?>)">Add to Cart</button>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        
        <h2 id="cart">Your Cart</h2>
        <div class="cart-container">
            <?php if(!empty($_SESSION['cart'])): ?>
            <table>
                <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
                <?php foreach($_SESSION['cart'] as $id=>$item): ?>
                <tr>
                    <td style="display:flex;gap:10px;align-items:center">
                        <div class="cart-item-image">
                            <img src="<?=$item['image']?>" alt="<?=$item['name']?>">
                        </div>
                        <?=$item['name']?>
                    </td>
                    <td>$<?=number_format($item['price'],2)?></td>
                    <td><?=$item['quantity']?></td>
                    <td>$<?=number_format($item['price']*$item['quantity'],2)?></td>
                    <td><button class="remove-btn" onclick="removeFromCart(<?=$id?>)">Remove</button></td>
                </tr>
                <?php endforeach ?>
            </table>
            <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart" style="font-size:40px"></i>
                <p>Your cart is empty</p>
            </div>
            <?php endif ?>
        </div>
        
        <?php if(!empty($_SESSION['cart'])): ?>
        <div class="summary">
            <div class="summary-row"><span>Subtotal:</span><span>$<?=number_format($subtotal,2)?></span></div>
            <div class="summary-row"><span>Shipping:</span><span>$5.99</span></div>
            <div class="summary-row total"><span>Total:</span><span>$<?=number_format($total,2)?></span></div>
            <button class="checkout-btn" onclick="checkout()">Checkout</button>
        </div>
        <?php endif ?>
    </div>
    
    <script>
        function addToCart(id) { window.location.href=`?action=add&id=${id}#cart` }
        function removeFromCart(id) { window.location.href=`?action=remove&id=${id}#cart` }
        function checkout() { if(confirm('Checkout?')) window.location.href='?action=checkout#cart' }
        function scrollToCart() { document.getElementById('cart').scrollIntoView({behavior:'smooth'}) }
    </script>
</body>
</html>