<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php80\NodeManipulator\AttributeGroupNamedArgumentManipulator;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Utils\Rector\Tests\Rector\RemoveAttrRector\RemoveAttrRectorTest
 */
final class RemoveAttrRector extends AbstractRector implements ConfigurableRectorInterface
{
    private $attrs = [];

    public function __construct(
        private PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private AttributeGroupNamedArgumentManipulator $attributeGroupNamedArgumentManipulator,
    ) { }

    public function configure(array $configuration): void
    {
        $this->attrs = array_merge($this->attrs, $configuration);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('// @todo fill the description', [
            new CodeSample(
                <<<'CODE_SAMPLE'
// @todo fill code before
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// @todo fill code after
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [ClassLike::class, Param::class, Property::class, ClassMethod::class];
    }

    /**
     * @param ClassLike|Param|Property|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->attrs as $searchAttr) {
            if (!$this->phpAttributeAnalyzer->hasPhpAttribute($node, $searchAttr)) {
                continue;
            }

            $unsetAttrGroup = [];
            foreach ($node->attrGroups as $j => $attrGroup) {
                $unset = [];
                foreach ($attrGroup->attrs as $i => $attr) {
                    if ($this->nodeNameResolver->getName($attr) === $searchAttr) {
                        $unset[] = $i;
                    }
                }

                if (empty($unset)) {
                    continue;
                }

                foreach ($unset as $i) {
                    unset($attrGroup->attrs[$i]);
                }

                if (empty($attrGroup->attrs)) {
                    $unsetAttrGroup[] = $j;
                }
            }

            foreach ($unsetAttrGroup as $f) {
                unset($node->attrGroups[$f]);
            }
        }


        return $node;
    }
}
