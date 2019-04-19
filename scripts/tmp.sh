python split_file_lst.py -f ../material/black/file.lst -w exec | while read f; do cp -iv "$f" /home/zhi/git_repo/mine/ml-script/black-list/; done

head -n 9 black_php_oper_order.train | tail -1 |  tr '\0' '#' | cut -d '#' -f 1
