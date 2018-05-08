<section>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th>Coin Tag</th>
            <th>Amount</th>
        </tr>
        </thead>
            <tbody>
                <tr>
                    <td>USD</td>
                    <td><?php echo $_SESSION['USD_BALANCE']; ?></td>
                </tr>
                <tr>
                    <td>BTC</td>
                    <td><?php echo $_SESSION['BTC_BALANCE']; ?></td>
                </tr>
                <tr>
                    <td>ETH</td>
                    <td><?php echo $_SESSION['ETH_BALANCE']; ?></td>
                </tr>
                <tr>
                    <td>TRX</td>
                    <td><?php echo $_SESSION['TRX_BALANCE']; ?></td>
                </tr>
                <tr>
                    <td>LTC</td>
                    <td><?php echo $_SESSION['LTC_BALANCE']; ?></td>
                </tr>
            </tbody>
    </table>
</section>

