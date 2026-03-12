<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Fetch category IDs by slug
        $cats = Category::pluck('id', 'slug');

        $elec  = $cats['electronica'];
        $ropa  = $cats['ropa-y-moda'];
        $hogar = $cats['hogar-y-decoracion'];
        $dep   = $cats['deportes-y-fitness'];
        $bell  = $cats['belleza-y-cuidado-personal'];
        $ali   = $cats['alimentos-y-bebidas'];
        $jug   = $cats['juguetes-y-juegos'];
        $lib   = $cats['libros-y-educacion'];

        // Products definition: [name, slug, price, quantity, description, category_ids[]]
        // First 6 are "bestsellers" (high weight in orders)
        $products_data = [
            // ── BESTSELLERS (índice 0-5) ─────────────────────────────────────
            ['Auriculares Bluetooth Pro',    'auriculares-bluetooth-pro',   59.99, 150, 'Auriculares inalámbricos con cancelación de ruido y 30h de batería', [$elec]],
            ['Camiseta Básica Unisex',       'camiseta-basica-unisex',      19.99, 300, 'Camiseta de algodón 100% disponible en varios colores',              [$ropa]],
            ['Proteína Whey Vainilla 1kg',   'proteina-whey-vainilla-1kg',  39.99, 200, 'Suplemento de proteína de suero de leche sabor vainilla',           [$dep, $ali]],
            ['Crema Hidratante Facial 50ml', 'crema-hidratante-facial-50ml',24.99, 250, 'Crema hidratante con ácido hialurónico y vitamina C',               [$bell]],
            ['Taza Térmica 500ml',           'taza-termica-500ml',          14.99, 400, 'Taza de acero inoxidable doble pared, mantiene temperatura 12h',    [$hogar]],
            ['Libro: Hábitos Atómicos',      'libro-habitos-atomicos',      18.99, 180, 'Bestseller de James Clear sobre el poder de los pequeños hábitos',  [$lib]],

            // ── PRODUCTOS MEDIOS (índice 6-15) ───────────────────────────────
            ['Smartwatch Fitness Band',      'smartwatch-fitness-band',     89.99,  80, 'Reloj inteligente con monitor de ritmo cardíaco y GPS',             [$elec, $dep]],
            ['Pantalón Deportivo Slim',      'pantalon-deportivo-slim',     34.99, 120, 'Pantalón de licra con bolsillos laterales, ideal para gym',         [$ropa, $dep]],
            ['Lámpara LED de Escritorio',    'lampara-led-escritorio',      29.99, 100, 'Lámpara ajustable con temperatura de color y puerto USB',           [$hogar, $elec]],
            ['Mochila Urbana 30L',           'mochila-urbana-30l',          49.99,  90, 'Mochila resistente al agua con compartimento para laptop 15"',      [$ropa, $dep]],
            ['Shampoo Reparador 400ml',      'shampoo-reparador-400ml',     12.99, 300, 'Shampoo con proteínas de seda para cabello dañado',                 [$bell]],
            ['Set de Cuchillos de Cocina',   'set-cuchillos-cocina',        54.99,  60, 'Juego de 6 cuchillos de acero inoxidable con soporte de madera',    [$hogar]],
            ['Pelota de Fútbol Profesional', 'pelota-futbol-profesional',   44.99,  70, 'Balón oficial tamaño 5, resistente a superficies irregulares',      [$dep]],
            ['Chocolates Artesanales 200g',  'chocolates-artesanales-200g', 16.99, 200, 'Surtido de chocolates belgas con rellenos variados',                [$ali]],
            ['LEGO Arquitectura Set 500pz',  'lego-arquitectura-set-500pz', 69.99,  50, 'Set de construcción para adultos, edifício icónico',                [$jug]],
            ['Libro: El Inversor Inteligente','libro-inversor-inteligente', 22.99, 110, 'Clásico de Benjamin Graham sobre inversión en valor',               [$lib]],

            // ── PRODUCTOS RAROS / NICHO (índice 16-29) ───────────────────────
            ['Monitor 27" 4K IPS',           'monitor-27-4k-ips',          349.99, 25, 'Monitor profesional 3840x2160, 60Hz, panel IPS con sRGB 99%',       [$elec]],
            ['Teclado Mecánico RGB',         'teclado-mecanico-rgb',       119.99,  40, 'Teclado gaming switches Cherry MX Red, retroiluminación RGB',       [$elec]],
            ['Chaqueta de Cuero Mujer',      'chaqueta-cuero-mujer',       149.99,  30, 'Chaqueta de cuero genuino estilo biker, forro interior',            [$ropa]],
            ['Vestido Floral Verano',        'vestido-floral-verano',       42.99,  80, 'Vestido ligero estampado floral, ideal para temporada cálida',      [$ropa]],
            ['Sofá 3 Plazas Gris',           'sofa-3-plazas-gris',         599.99,  10, 'Sofá tapizado en tela antimanchas, estructura de madera maciza',    [$hogar]],
            ['Cafetera Espresso Automática', 'cafetera-espresso-automatica',199.99, 20, 'Cafetera con molinillo integrado, presión 15 bar',                  [$hogar, $ali]],
            ['Bicicleta MTB Aluminio 29"',   'bicicleta-mtb-aluminio-29',  699.99,  12, 'Bicicleta de montaña 21 velocidades, frenos de disco hidráulico',   [$dep]],
            ['Esterilla Yoga Antideslizante','esterilla-yoga-antideslizante',27.99, 90, 'Esterilla 6mm de grosor, superficie antideslizante doble cara',     [$dep, $bell]],
            ['Perfume Floral Femenino 100ml','perfume-floral-femenino-100ml',79.99, 45, 'Eau de Parfum con notas de jazmín, rosa y almizcle blanco',         [$bell]],
            ['Vino Tinto Reserva 750ml',     'vino-tinto-reserva-750ml',    18.99, 120, 'Vino tinto con 12 meses en barrica, notas de frutos rojos',         [$ali]],
            ['Café Gourmet Molido 500g',     'cafe-gourmet-molido-500g',    14.99, 200, 'Café de origen único, tostado medio, notas de chocolate',           [$ali]],
            ['Puzzle 1000 piezas Mundo',     'puzzle-1000-piezas-mundo',    24.99,  60, 'Puzzle del mapa mundial en alta resolución, 1000 piezas',           [$jug]],
            ['Dron Fotográfico Plegable',    'dron-fotografico-plegable',   249.99, 18, 'Dron con cámara 4K, autonomía 25 min, estabilizador óptico',        [$elec]],
            ['Libro: El Poder del Ahora',    'libro-el-poder-del-ahora',    15.99, 140, 'Guía espiritual de Eckhart Tolle sobre el presente consciente',     [$lib]],
        ];

        $products = [];
        $product_ids = [];

        foreach ($products_data as $data) {
            $id = Str::uuid()->toString();
            $product_ids[] = $id;

            $products[] = [
                'id'          => $id,
                'name'        => $data[0],
                'slug'        => $data[1],
                'price'       => $data[2],
                'quantity'    => $data[3],
                'description' => $data[4],
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        Product::insert($products);

        // Build category_product pivot records
        $pivot = [];
        foreach ($products_data as $index => $data) {
            foreach ($data[5] as $cat_id) {
                $pivot[] = [
                    'category_id' => $cat_id,
                    'product_id'  => $product_ids[$index],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('category_product')->insert($pivot);
    }
}
