    <div id="user-profile-1" class="user-profile row">
        <div class="col-xs-12 col-sm-12">
        <div class="profile-user-info profile-user-info-striped">
            <div class="profile-info-row">
                <div class="profile-info-name">Tên khách hàng</div>
                <div class="profile-info-value">
                    <span class="editable editable-click" id="username">{{ $customer->Name }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Mã khách hàng</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->CustomerCode }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Email</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->Email }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Số điện thoại</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->Tel }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Ngày sinh</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ date("d/m/Y", strtotime($customer->BirthDay)) }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Nhóm</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->GroupName }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Người phụ trách</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->ManagerBy }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Mô tả</div>
                <div class="profile-info-value">
                    <span class="editable" id="age">{{ $customer->Description }}</span>
                </div>
            </div>
        </div>
        </div>
    </div>
