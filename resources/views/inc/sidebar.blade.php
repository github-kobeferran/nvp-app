
  
<span class="slide mx-3 mb-2">
    <a href="#" onClick="openSlideMenu()">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </a>
</span>
  
<div id="menu" {!!auth()->user()->isAdmin() ? 'class="admin-sidebar"' : 'class="sidebar"'!!}>
    <a href="#" class="close" onClick="closeSlideMenu()">
        <i class="fa fa-times"></i>
    </a>

    @if (auth()->user()->isAdmin())

        <a href="/admin">Dashboard</a>
        <a href="/admin/orders">Orders</a>
        <a href="/admin/clients">Clients</a>
        <a href="/admin/pets">Pets</a>
        <a href="/admin/inventory">Inventory</a>
        <a href="/admin/services">Services</a>

        @if (is_null(auth()->user()->employee))
            <a href="/admin/employees">Employees</a>                        
        @endif
        
        
    @else

        <a href="/user">My Profile</a>        
        <a href="/myorders/{{auth()->user()->email}}">Orders</a>
        <a href="/transactions/{{auth()->user()->email}}">Transactions</a>
        
    @endif

    
</div>


<script>

function openSlideMenu () {
    document.getElementById('menu').style.width = '250px';    
}

function closeSlideMenu () {
    document.getElementById('menu').style.width = '0';
}
      
</script>