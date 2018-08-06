<?php
declare(strict_types=1);

namespace adeynes\cucumber\command;

class ParsedCommand
{

    /** @var string */
    protected $name;

    /** @var string[] */
    protected $args;

    /** @var string[] */
    protected $tags;

    /**
     * @param string $name
     * @param string[] $args
     * @param string[] $tags
     */
    public function __construct(string $name, array $args, array $tags)
    {
        $this->name = $name;
        $this->args = $args;
        $this->tags = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieve the specified arguments
     * @param int[]|int[][] $requests An integer will retrieve the element at that index,
     * an $array will implode the elements from $array[0] with length $array[1]
     * i.e. to get ['hello world', 'argument', 'cucumber is cool'] from the arguments
     * 'cucumber is cool hello world argument', you would request [[3, 2], -1, [0, 3]]
     * @return string[]
     */
    public function get(array $requests): array
    {
        $args = [];

        foreach ($requests as $request) {
            if (is_array($request)) {
                // $request[0] is offset, $request[1] is length. Negative length means start from back
                if ($request[1] < 0) {
                    $request[1] = count($this->getArgs()) - $request[0];
                }

                $args[] = trim(implode(' ', array_slice($this->getArgs(), ...$request)));
            } else {
                // array_slice instead of access to allow negative offsets
                $args[] = array_slice($this->getArgs(), $request, 1)[0];
            }
        }

        return $args;
    }

    /**
     * @return string[]
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTag(string $tag): ?string
    {
        return $this->getTags()[$tag] ?? null;
    }

}