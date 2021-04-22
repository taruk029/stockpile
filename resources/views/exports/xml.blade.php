<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Date</th>
            <th>Trainer</th>
            <th>Region</th>
            <th>Distributor Code</th>
            <th>Distributor Name</th>
            <th>DBSR Code</th>
            <th>DBSR Name</th>
            <th>Range Compliance</th>
            <th>MSL</th>
            <th>No of outlets in the beat</th>
            <th>No of Outlets Covered</th>
            <th>No of Productive outlets</th>
            <th>No of Unproductive Outlets</th>
            <th>Reasons for unproductivity</th>
        </tr>
    </thead>

    <tbody>
        <?php $i = 1; ?>
        @foreach($plans as $row )
        <tr>
            <td>{{ $i }}</td>
            <td>{{ Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
            <td>{{ $row->trainer_name  }}</td>
            <td>{{ $row->region_name  }}</td>
            <td>{{ $row->distributor_code  }}</td>
            <td>{{ $row->distributor_name  }}</td>
            <td>{{ $row->dbsr_code  }}</td>
            <td>{{ $row->dbsr_name  }}</td>
            <td>{{ $row->range_compliance?$row->range_compliance:''}}</td>
            <td>{{ $row->msl?$row->msl:''}}</td>
            <td>
                {{ App\Helpers\Helper::get_total_beats($row->plan_id)?App\Helpers\Helper::get_total_beats($row->plan_id):"NA" }}</td>
            <td>
                {{ App\Helpers\Helper::get_covered_outlets($row->plan_id)?App\Helpers\Helper::get_covered_outlets($row->plan_id):"NA" }}
            </td>
            <td>
                {{ App\Helpers\Helper::get_productive_outlets($row->plan_id)?App\Helpers\Helper::get_productive_outlets($row->plan_id):"NA" }}
            </td>
            <td>
                {{ App\Helpers\Helper::get_unproductive_outlets($row->plan_id)?App\Helpers\Helper::get_unproductive_outlets($row->plan_id):"NA" }}
            </td>
            <td>
                <?php echo App\Helpers\Helper::get_unproductive_reason($row->plan_id)?App\Helpers\Helper::get_unproductive_reason($row->plan_id):"NA" ?>
            </td>
        </tr>
      <?php $i++; ?>  
    @endforeach                
    </tbody>
</table>