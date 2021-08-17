    <div class="page-buffer"></div>
</div>

<footer id="footer" class="page-footer"><!--Footer-->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <p class="pull-left">Copyright © 2015</p>
                <p class="pull-right">Курс PHP Start</p>
            </div>
        </div>
    </div>
</footer><!--/Footer-->



<script src="/template/js/jquery.js"></script>
<script src="/template/js/jquery.cycle2.min.js"></script>
<script src="/template/js/jquery.cycle2.carousel.min.js"></script>
<script src="/template/js/bootstrap.min.js"></script>
<script src="/template/js/jquery.scrollUp.min.js"></script>
<script src="/template/js/price-range.js"></script>
<script src="/template/js/jquery.prettyPhoto.js"></script>
<script src="/template/js/main.js"></script>
<script>
    $(document).ready(function(){
        // добавление товаров в корзину
        function addToCart(e){
            var elem = e.target;
            var id = $(elem).attr("data-id");
            $.post("/cart/addAjax/"+id, {}, function (data) {
                $("#cart-count").html(data);
            });
            return false;
        }
        // на родительский элемент при подгрузке аякс запросом
        $(".products-container").click(function (e) {
            if(e.target.classList.contains('add-to-cart')){
                addToCart(e);
            }

        });

        // фильтрация товаров по ценам
        $('.filter-btn').click(function(){
            let from;
            let to;
            let price = $('input.price-filter:checked').data('price');
            let html;
            switch(price){
                case 'low':
                from = 0; to = 500;
                break;
                case 'middle':
                from = 500; to = 1000;
                break;
                case 'high':
                from = 1000; to = 10000
                break;
            }
            
            $.ajax({
                url: '/catalog/filterajax',
                type: 'POST',
                data: {from, to},
                beforeSend: function(){
                    $('.products-container .single-products').css({opacity: .3})
                    var preloader = document.createElement('img')
                    preloader.src = 'http://onlineshop/template/images/preloader.gif' 
                    preloader.classList.add('products-container-loader')
                    $('.products-container').append(preloader)
                },
                success: function(res){
                    setTimeout(() => {
                    $('.products-container').html(html);
                    preloader.remove();
                    $('.products-container .single-products').css({opacity: 1})

                        return true;
                    }, 1000);
                const arr = JSON.parse(res);
                console.log(arr.length);
                html = arr.map((product) => {
                        return `<div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="/upload/images/products/${product.id}.jpg" alt="product" />
                                        <h2>${product.price}</h2>
                                        <p>
                                            <a href="/product/${product.id}">
                                                ${product.name}
                                            </a>
                                        </p>
                                        
                                        <a href="#" data-id="${product.id}"
                                           class="btn btn-default add-to-cart">
                                            <i class="fa fa-shopping-cart"></i>В корзину
                                        </a>
                                    </div>
                                    ${product.is_new ? `<img src="/template/images/home/new.png" class="new" alt="" />` : ``}
                                </div>
                            </div>
                        </div>`
                    }).join('');
                }
            })
            return false;
        });
    });
</script>

</body>
</html>