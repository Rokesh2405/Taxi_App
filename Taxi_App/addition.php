<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>How to sum values from table column and update when remove or add new row in jQuery </title>
    <style type="text/css">
        #TableHead td {
            border-bottom: 1px #000 solid;
        }

        .orderTotalCell,
        #grandTotalCell,
        #totalPriceCell {
            text-align: right;
        }

        #TableFooter tr:first-child td {
            border-top: 1px #000 solid;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        var $tableBody = $('#TableBody');
        var $totalQuantityCell = $('#totalQuantityCell');
        var $totalPriceCell = $('#totalPriceCell');
        var $totalGrandCell = $('#grandTotalCell');
        // Add a row with random values on "Add Row" button click
        $('#xd').click(addRandomRow);

        function addRandomRow(event) {
            var randomCode = Math.round(Math.random() * 4);
            var randomClient = Math.round(Math.random() * 15);
            var randomCharge = (Math.round(Math.random()) ? 'Debit' : 'Credit');
            var randomQuantity = Math.ceil(Math.random() * 5);
            var randomPrice = Math.ceil(Math.random() * 100).toFixed(2);
            addRow(randomCode, randomClient, randomCharge, randomQuantity, randomPrice);
        };
        // Add some rows to start
        addRandomRow();
        addRandomRow();
        // Listen for clicks on ".deleteRowButton" within the table
        $tableBody.on('click', '.deleteRowButton', function(event) {
            deleteRow($(event.target).data('row'));
            updateTotals();
        });

        function addRow(code, client, chargeType, quantity, price) {
            // Create a new row element
            var idNum = ($tableBody.find('tr').length + 1);
            var rowId = 'row-' + idNum;
            var $row = $('<tr id="' + rowId + '"></tr>');
            // Add the table cells $row.append('<td class="idCell">' + idNum + '</td>');
            $row.append('<td class="codeCell">' + code + '</td>');
            $row.append('<td class="clientCell">' + client + '</td>');
            $row.append('<td class="chargeTypeCell">' + chargeType + '</td>');
            $row.append('<td class="quantityCell">' + quantity + '</td>');
            $row.append('<td class="priceCell">' + price + '</td>');
            $row.append('<td class="orderTotalCell">' + getSubtotal(quantity, price) + '</td>');
            $row.append('<td><input type="button" value="Delete" class="deleteRowButton" data-row="#' + rowId + '" /></td>');
            // Append the row to the table body
            $tableBody.append($row);
            updateTotals();
        }

        function deleteRow(rowId) {
            $(rowId).remove();
        }

        function updateTotals() {
            var totalQuantity = getColumnTotal('.quantityCell');
            var totalPrice = getColumnTotal('.priceCell');
            var totalOrder = getColumnTotal('.orderTotalCell');
            $totalQuantityCell.text(totalQuantity);
            $totalPriceCell.text(toMoney(totalPrice));
            $totalGrandCell.text(toMoney(totalOrder));
        }

        function getSubtotal(quantity, price) {
            return (quantity * price).toFixed(2);
        }

        function getColumnTotal(selector) {
            return Array.from($(selector)).reduce(sumReducer, 0);
        }

        function sumReducer(total, cell) {
            return total += parseInt(cell.innerHTML, 10);
        }

        function toMoney(number) {
            return '$' + number.toFixed(2);
        }
    </script>
</head>
<body>
    <table id="Table">
        <thead id="TableHead">
            <tr>
                <td>ID</td>
                <td>Code</td>
                <td>Client</td>
                <td>Debit/Credit</td>
                <td>Quantity</td>
                <td>Price</td>
                <td>Order Total</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody id="TableBody">
        </tbody>
        <tfoot id="TableFooter">
            <tr>
                <td colspan="4">Sub-Total</td>
                <td id="totalQuantityCell">–</td>
                <td id="totalPriceCell">–</td>
                <td id="grandTotalCell">–</td>
            </tr>
        </tfoot>
    </table>
    <input type="button" id="xd" value="add row">
</body>
</html>