<?php

// it show create product form
it('can show create product form', function () {
    $this->get(route('products.create'))
        ->assertComponent('Products/Create');
});
