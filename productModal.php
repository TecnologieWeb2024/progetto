<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="col-12">
                    <button type="button" class="btn-close float-end pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="container modal-body d-flex p-0 pt-4">
                        <div class="col-md-6 col-4">
                            <img id="modalProductImage" src="assets/img/image1.jpg" class="img-fluid" alt="placeholder">
                        </div>
                        <div class="col-md-6 col-8 d-flex flex-column">
                            <div class="row">
                                <h2 class="h3" id="modalProductName">Placeholder</h2>
                                <p id="modalProductDescription">Placeholder</p>
                                <?php if (isUserSeller()): ?>
                                    <p class="text-muted">In magazzino: <span id="modalProductStock">-</span></p>
                                    <p class="text-muted">ID prodotto: <span id="modalProductId">-</span></p>
                                <?php endif; ?>
                            </div>
                            <div class="row mt-auto">
                                <p id="modalProductPrice" class="text-end h3"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container d-flex justify-content-center">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <?php if (isUserCustomer()) : ?>
                                    <div class="d-flex align-items-center border border-1 rounded ms-2 me-2">
                                        <button type="button" class="quantity-right-plus btn btn-secondary btn-number rounded rounded-0 rounded-start" data-type="minus" data-field="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="pointer-events: none;">
                                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"></path>
                                            </svg>
                                        </button>
                                        <label for="modalQuantity" class="visually-hidden">Quantità</label>
                                        <input type="number" id="modalQuantity" name="quantity" class="form-control text-center rounded rounded-0 w-auto" value="1" min="1" max="1" style="max-width: 4em !important;">
                                        <button type="button" class="quantity-right-plus btn btn-secondary btn-number rounded rounded-0 rounded-end" data-type="plus" data-field="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="pointer-events: none;">
                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <?php if (!isUserLoggedIn()): ?>
                                        <a href="#" class="btn btn-primary float-end w-100 w-md-25 mt-2" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#loginModal"><em class="fa fa-cart-plus"></em></a>
                                    <?php else: ?>
                                        <a href="#" title="add-to-cart" class="btn btn-primary btn-add-to-cart float-end w-100 w-md-25 mt-2"
                                            data-product-id="<?php echo $product['product_id']; ?>">
                                            <em class="fa fa-cart-plus"></em>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>