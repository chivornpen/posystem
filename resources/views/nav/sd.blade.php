<li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#Customers"><i class="fa fa-fw fa-group"></i> Customer <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="Customers" class="collapse nav nav-second-level">
                                <li>
                                    <a href="{{ route('customers.index')}}">All Customers</a>
                                </li>
                                <li>
                                    <a href="{{ route('customers.create')}}">Create New Customer</a>
                                </li>
                            </ul>
</li>
{{--------------- start SD---------------}}
{{--------------------Order from office---------------}}
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#PurchaseOrderSD"><i class="fa fa-shopping-basket" aria-hidden="true"></i></i> To Supplier<i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="PurchaseOrderSD" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('purchaseOrdersSD.index')}}">All Purchase Order</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('purchaseOrdersSD.create')}}">Create New Order</a>
                                        </li>
                                    </ul>
                                </li>
    {{-----------------------end-----------}}
    {{--------------------Order from office---------------}}
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#SDsale"><i class="fa fa-shopping-basket" aria-hidden="true"></i></i> To Customer<i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="SDsale" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('saleSD.index')}}">All Purchase Order</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('saleSD.create')}}">Create New Order</a>
                                        </li>
                                    </ul>
                                </li>
    {{-----------------------end-----------}}

 {{--SD STOCK MANAGEMENT MENU--}}
                        <li><a href="javascript:;" data-toggle="collapse" data-target="#sdStock"><i class="fa fa-industry" aria-hidden="true"></i> Stock Management <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="sdStock" class="collapse nav nav-second-level">

                                {{--SD stock_in_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#sDstockin"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Stock In <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="sDstockin" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('sdstock.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end SD stock-in-menu--}}


                                {{--SD stock_out_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#sDstockout"><i class="fa fa-truck" aria-hidden="true"></i> Stock Out <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="sDstockout" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('stockoutsd.create')}}">Export</a>
                                        </li>
                                        <li>
                                            <a href="{{route('stockoutsd.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end stock-out-menu--}}
                            </ul>
                        </li>


                        {{--Exchange_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#exchange"><i class="fa fa-exchange" aria-hidden="true"></i> Product Exchange SD <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="exchange" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('exchangesd.create')}}">Exchange</a>
                                        </li>
                                        <li>
                                            <a href="{{route('exchangesd.index')}}">Views</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/createPoExchange')}}"><i class="fa fa-exchange" aria-hidden="true"></i> Create Invoice Exchange </a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end Exchange-menu--}}
                                {{--return_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#return"><i class="fa fa-retweet" aria-hidden="true"></i> Product Return <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="return" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('productreturnsd.create')}}">Return</a>
                                        </li>
                                        <li>
                                            <a href="{{route('productreturnsd.index')}}">Views</a>
                                        </li>
                                        {{--create invoice product return--}}
                                        <li>
                                            <a href="{{URL::to('/admin/createInvoiceReturnzSd')}}"><i class="fa fa-share" aria-hidden="true"></i> Create Invoice Return</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end return-menu--}}


                                {{--SD stock_in_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#sdstockReport"><i class="fa fa-shopping-cart" aria-hidden="true"></i> SD Stock Report <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="sdstockReport" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('sdstockreport.create')}}">Report Stock In</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/sdreportstockout')}}">Report Stock Out</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('sdstockreport.index')}}">Report Stock Balance</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/reportStockExchange')}}">Report Stock Exchange</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/reportStockReturn')}}">Report Stock Return</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end SD stock-in-menu--}}