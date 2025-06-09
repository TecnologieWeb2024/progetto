<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h3 class="h5 modal-title">Dettagli Ordine #<span id="modalOrderId">–</span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="container-fluid">

                    <!-- Sezione riepilogo ordine -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Data:</strong> <span id="modalOrderDate">–</span></p>
                            <p><strong>Totale:</strong> <span id="modalOrderTotal">–</span></p>
                            <p><strong>Stato:</strong> <span id="modalOrderStatus">–</span></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Pagamento:</strong> <span id="modalPaymentStatus">–</span></p>
                            <p><strong>Metodo:</strong> <span id="modalPaymentMethod">–</span></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabella prodotti nell'ordine -->
                    <div class="row mb-3">
                        <div class="col-12 table-responsive">
                            <table class="table table-sm table-striped caption-top">
                                <caption>Prodotti ordinati</caption>
                                <thead class="">
                                    <tr>
                                        <th>Prodotto</th>
                                        <th>Prezzo Unitario</th>
                                        <th>Quantità</th>
                                        <th>Subtotale</th>
                                    </tr>
                                </thead>
                                <tbody id="modalOrderItems" class=""></tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Sezione spedizione -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Indirizzo di consegna:</strong></p>
                            <p id="modalShipmentAddress">–</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Metodo di spedizione:</strong> <span id="modalShippingMethod">–</span></p>
                            <p><strong>Stato spedizione:</strong> <span id="modalShipmentStatus">–</span></p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <?php if (isUserCustomer()): ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" id="btnAcceptOrder" onclick="acceptOrder()">Accetta</button>
                    <button type="button" class="btn btn-danger" id="btnCancelOrder" onclick="cancelOrder()">Annulla</button>
                    <button type="button" class="btn btn-secondary" id="btnAdvanceStatus" onclick="advanceStatus()">Avanza Stato</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="js/orderModal.js"></script>