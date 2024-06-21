<?php

namespace Utils\Rector\Tests\Rector\RemoveAttrRector\Fixture;

use Attribute;

#[Attribute()]
class Test
{
}

?>
-----
<?php

namespace Utils\Rector\Tests\Rector\RemoveAttrRector\Fixture;

use Attribute;

class Test
{
}

?>
