use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_neda extends Model implements AuditableContract
{
    use Auditable;
}
