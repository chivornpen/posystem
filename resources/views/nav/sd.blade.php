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
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#POSD"><i class="fa fa-money" aria-hidden="true"></i> SD Managment<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="POSD" class="collapse nav nav-second-level">
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#PurchaseOrderSD"><i class="fa fa-shopping-basket" aria-hidden="true"></i></i> Purchase Order Office<i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="PurchaseOrderSD" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('purchaseOrdersSD.index')}}">All Purchase Order</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('purchaseOrdersSD.create')}}">Create New Order</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#SDsale"><i class="fa fa-shopping-basket" aria-hidden="true"></i></i> Sale To Customer<i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="SDsale" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('saleSD.index')}}">All Purchase Order</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('saleSD.create')}}">Create New Order</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        {{---------------end sd--------}}