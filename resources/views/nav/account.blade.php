<li>
                            <a href="{{route('dashbords.index') }}"><i class="fa fa-bell fa-fw"></i> Notifications</a>
</li>
{{-------Account---------------}}
                                <li>
                                    <a href="{{ route('invoicePO.index')}}">New Invoice</a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#summa"><i class="fa fa-list-alt" aria-hidden="true"></i> Summary Invoices <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="summa" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{ route('summaryInvs.index')}}">&nbsp; &nbsp;&nbsp;&nbsp;All Summary Invocie</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('summaryInvs.create')}}"> &nbsp;&nbsp;&nbsp;&nbsp;Create Summary Invocie</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{route('invoicePO.create')}}"><i class="fa fa-exchange" aria-hidden="true"></i> Create Invoice Exchange </a>
                                </li>

                                {{--create invoice product return--}}
                                <li>
                                    <a href="{{url('/invoicePo/ProductReturn/view')}}"><i class="fa fa-share" aria-hidden="true"></i> Create Invoice Return</a>
                                </li>

                        {{-------end account--------------}}
                        {{--customer report--}}
                        <li><a href="javascript:;" data-toggle="collapse" data-target="#Report"><i class="fa fa-file-text" aria-hidden="true"></i> Report <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="Report" class="collapse nav nav-second-level">

                                <li><a href="javascript:;" data-toggle="collapse" data-target="#CutomerList"><i class="fa fa fa-users" aria-hidden="true"></i> Customer Report<i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="CutomerList" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{route('report.index')}}">Customer List</a>
                                        </li>
                                    </ul>
                                </li>


                                {{--sale report--}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#saleReport"><i class="fa fa fa-archive" aria-hidden="true"></i> Sale Reports <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="saleReport" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{url('report/sale/view')}}">Sale Report</a>
                                        </li>
                                    </ul>
                                </li>
                                 {{--Payment Report--}}
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#paymentReport"><i class="fa fa fa-paypal" aria-hidden="true"></i> Payment Reports <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="paymentReport" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{url('/report/payment/views/report')}}">Payment Report</a>
                                        </li>
                                    </ul>
                                </li>

                                <li><a href="javascript:;" data-toggle="collapse" data-target="#customerCredit"><i class="fa fa fa-cc" aria-hidden="true"></i> Customer Credit <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="customerCredit" class="collapse nav nav-second-level">
                                        <li>
                                            <a href="{{url('/report/customerCredit/views/report')}}">Customer Credit Report</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>