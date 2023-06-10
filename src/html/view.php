<?php
include('head.php');
?>

<body>
    <header class="sticky top-0 p-3 border-b-2 border-slate-700 flex place-content-between bg-indigo-200 bg-opacity-50 h-16 w-full sm:h-20 z-50 ">
        <a class="font-mono text-2xl sm:text-5xl ml-3 leading-[2.5rem] sm:leading-[3.5rem]" href="#">Book shelf</a>
        <ul class="flex text-lg sm:text-2xl leading-[2.5rem] sm:leading-[3.5rem]">
            <?php foreach ($status as $state => $value) : ?>
                <li class="mr-6"><a href="#<?php echo $value ?>"><?php echo $state ?></a></li>
            <?php endforeach; ?>
        </ul>
    </header>
    <main class="xl:flex">
        <?php
        include('form.php'); ?>
        <div>
            <?php
            foreach ($status as $state => $value) : ?>
                <div class="my-8 container">
                    <h2 id=<?php echo $value ?> class="text-4xl pt-20 -mt-20"><?php echo $state ?></h2>
                    <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 md:gap-8 lg:grid-cols-3 lg:gap-10 xl:grid-cols-4 xl:gap-12 2xl:gap-14 my-4">
                        <?php $lists = getLists($pdo, $state);
                        foreach ($lists as $list) :
                            $id = $list['id']; ?>
                            <section class="relative" onmouseleave="heddenDropdown(<?php echo $id ?>)">
                                <img src="<?php echo $list['image_url'] ?>" alt="" />
                                <div class="absolute top-0 right-0">
                                    <div class="relative inline-block text-left dropdown">
                                        <button onclick="dropdown(<?php echo $id ?>)" type="button" class="dropdown-btn inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <form action="edit.php" method="POST">
                                            <div id="<?php echo $id ?>" class="dropdown-con origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none transition ease-in-out duration-100 transform opacity-0 scale-95 pointer-events-none" tabindex="-1">
                                                <div role="none">
                                                    <button type="submit" name="edit" value="<?php echo $id ?>" class="text-gray-700 block w-full text-left px-4 py-2 text-sm" tabindex="-1">編集</button>
                                                    <button onclick="return checkDelete()" type="submit" name="delete" value="<?php echo $id ?>" class="text-gray-700 bg-red-300 block w-full text-left px-4 py-2 text-sm rounded-b-md" tabindex="-1">
                                                        削除
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <h3 class="text-lg"><?php echo escape($list['title']) ?></h3>
                                <h4><?php echo ($list['mediums']) ?></h4>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="./main.js"></script>
</body>

</html>