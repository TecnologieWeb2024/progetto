<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="col-12">
                    <div class="container modal-body d-flex p-0 pt-4">
                        <div class="col-md-6 col-4">
                            <img id="modalProductImage" src="" class="img-fluid" alt="">
                        </div>
                        <div class="col-md-6 col-8 d-flex flex-column">
                            <div class="row">
                                <h3 id="modalProductName"></h3>
                                <p id="modalProductDescription"></p>
                            </div>
                            <div class="row mt-auto">
                                <p id="modalProductPrice" class="text-end h3"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container d-flex justify-content-center">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center border border-1 rounded ms-2 me-2">
                                    <button type="button" class="quantity-left-minus btn btn-secondary btn-number rounded rounded-0 rounded-start" data-type="minus" data-field="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"></path>
                                        </svg>
                                    </button>
                                    <label for="quantity" class="visually-hidden">Quantità</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control text-center rounded rounded-0" value="1" min="1" max="<?php echo $product['stock'] ?>" style="width:3em">
                                    <button type="button" class="quantity-right-plus btn btn-secondary btn-number rounded rounded-0 rounded-end" data-type="plus" data-field="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <a href="#" title="add-to-cart" class="btn btn-primary float-end w-25 me-2"><em class="fa fa-cart-plus"></em></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>