<li>
                            <a href="{{route('dashbords.index') }}"><i class="fa fa-bell fa-fw"></i> Notifications</a>
</li>
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#cat"><i class="fa fa-tags" aria-hidden="true"></i> Category <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="cat" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('categories.index')}}">All Categories</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('categories.create')}}">Create New Category</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#Pro"><i class="fa fa-product-hunt" aria-hidden="true"></i> Product <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="Pro" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('products.index')}}">All Products</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('products.create')}}">Create New Product</a>
                                        </li>
                                    </ul>
                                </li>
                               {{--stock_in_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#stockin"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Stock In <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="stockin" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{URL::to('/admin/stock')}}">Add New</a>
                                        </li>
                                        <li>
                                            <a href="{{route('stock.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end stock-in-menu--}}


                                {{--stock_out_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#stockout"><i class="fa fa-truck" aria-hidden="true"></i> Stock Out <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="stockout" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('stockout.create')}}">Export</a>
                                        </li>
                                        <li>
                                            <a href="{{route('stockout.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end stock-out-menu--}}


                                {{--Exchange_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#exchange"><i class="fa fa-exchange" aria-hidden="true"></i> Product Exchange <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="exchange" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('exchange.create')}}">Exchange</a>
                                        </li>
                                        <li>
                                            <a href="{{route('exchange.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end Exchange-menu--}}


                                {{--return_menu --}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#return"><i class="fa fa-retweet" aria-hidden="true"></i> Product Return <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="return" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('return.create')}}">Return</a>
                                        </li>
                                        <li>
                                            <a href="{{route('return.index')}}">Views</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end return-menu--}}
                                
                        <li><a href="javascript:;" data-toggle="collapse" data-target="#stockReport"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Stock Report <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="stockReport" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('stockreport.create')}}">Report Stock In</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/reportStockOut')}}">Report Stock Out</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('stockreport.index')}}">Report Stock Balance</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/reportStockExchange')}}">Report Stock Exchange</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::to('/admin/reportStockReturn')}}">Report Stock Return</a>
                                        </li>
                                        <li>
                                             <a href="{{URL::to('/report/expired/prouduct')}}">Expired Products</a>
                                        </li>
                                    </ul>
                                </li>

                                {{--end SD stock-in-menu--}}