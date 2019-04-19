#!/usr/bin/python

import sys
import argparse
import os.path

def get_file_name_with_cat(f_n_c_l, wc_l):
    new_fncl = []
    for fn, fc in f_n_c_l:
        if fc in wc_l: new_fncl.append((fn, fc))
    return new_fncl

def get_file_name_without_cat(f_n_c_l, wtc_l):
    new_fncl = []
    for fn, fc in f_n_c_l:
        if fc not in wtc_l: new_fncl.append((fn, fc))
    return new_fncl

def get_file_cats(f_n_c_l):
    # dct key is cat, value is count
    dct = {}
    for _, c in f_n_c_l:
        if c in dct.keys(): dct[c] = dct[c]+1
        else: dct[c] = 1
        
    return dct

def dump_file_name(f_n_c_l):
    for fn, _ in f_n_c_l:
        print fn

def dump_file_name_cat(f_n_c_l):
    for fn, cat in f_n_c_l:
        print fn, cat
        
def get_file_name_cat(fn):
    invalid_cat = ('dat', 'database', 'dynamic_call', 'evak', 'eval_unknown', 'exec_http', 'non-php', 'non-webshell', 'preg_replace', 'read_file', 'word', 'write_file', 'gen', 'inc', 'html')
    f = open(fn)
    dir_n = os.path.dirname(fn)
    if len(dir_n) == 0: dir_n = '.'
    file_cat_l = []
    for l in f:
        try:
            sp_i = l.index('    ')
            fn = l[:sp_i]
            cat = l[sp_i+4:-1]
            abs_f_path = dir_n+'/'+fn
            if not os.path.exists(abs_f_path):
                print l,
                continue

            if cat[0] == ' ':
                print l
                continue
            if ' ' in cat:
                sp_i2 = cat.index(' ')
                cat = cat[:sp_i2]
            if '/' in cat:
                sp_i3 = cat.index('/')
                cat = cat[:sp_i3]
                
            if cat in invalid_cat:
                print l,
                continue
            file_cat_l.append((abs_f_path, cat))
        except:
            print l

    f.close()
    return file_cat_l

    
if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument("-w", "--with", nargs="*", help="with categories")
    parser.add_argument("-n", "--without", nargs="*", help="without categories")
    parser.add_argument("-c", "--categories", action="store_true", help="print catetories")
    parser.add_argument("-f", "--file", action='store', help="file containing list")
    args = parser.parse_args(sys.argv[1:])
    arg_vars = vars(args)
    
    f_n_c_l = get_file_name_cat(arg_vars['file'])

    if arg_vars['with'] is not None and len(arg_vars['with']) > 0:
        fnl = get_file_name_with_cat(f_n_c_l, arg_vars['with'])
        dump_file_name(fnl)

    if arg_vars['without'] is not None and len(arg_vars['without']) > 0:
        fnl = get_file_name_without_cat(f_n_c_l, arg_vars['without'])
        dump_file_name(fnl)

    if arg_vars['categories'] == True:
        dct = get_file_cats(f_n_c_l)
        items = dct.items()
        items_s = sorted(items, key=lambda v: v[1])
        
        for k, v in items_s:
            print k, ':', v
