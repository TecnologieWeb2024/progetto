<div class="container">
    <div class="row">
        <?php for ($i = 0; $i < 4; $i++) : ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card mb-4">
                    <img src="image'<?php echo $i ?>'.jpg" class="card-img-top" alt="Product <?php echo $i ?>">
                    <div class="card-body">
                        <h5 class="card-title">Product <?php echo $i ?></h5>
                        <p class="card-text"><?php echo $i ?>.00</p>
                        <a href="#" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>