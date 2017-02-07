<form method="post" action="bank.php" id="form">
<table width="100%" border="0" cellpadding="3" cellspacing="3">
    <tr>
    	<td colspan="2" align="center">
        	<h2>Nạp tiền qua bank</h2>
        </td> 
    </tr>
   
    <tr>
    	
    	<td align="right">
        	Nhập số tiền :
        </td>
        <td>
                <input type="number" id="amount" name="amount" placeholder="lớn hơn 10.000"/>
        </td>
    </tr>
    <tr><td align="right">
        	Order id :
        </td>
        <td>
        <!--Please INSERT value for order_id -->
            <input type="text" value=""  id="order_id" name="order_id" placeholder="mã order nhỏ hơn 50 ký tự" />
        </td>
    </tr>
    <tr><td align="right">
        	Nhập order info :
        </td>
        <td>
        	<input type="text" id="order_info" name="order_info" placeholder="thông tin order" />
        </td>
    </tr>
     <tr>
    	<td align="right">
        	
        </td>
        <td>
        	<input type="submit" value="Nạp bank" />
        </td>
    </tr>
</table>
</form>