import numpy as np
import pandas as pd


def combine(element, itemset):
    """
    Une el elemento 'element' con todas las posibles combinaciones del
    itemset y lo retorna como lista
    """
    if len(itemset) == 0:
        yield np.array([element])
        return
    
    if len(itemset) == 1:
        yield np.append(np.array([element]), itemset)
        return
    
    for combination in combination_step(itemset[0], itemset[1:]):
        yield np.append(np.array([element]), combination)


def combination_step(head, items):
    """
    Paso recursivo de la funcion combine
    """
    
    if len(items) == 1:
        item = items[0]
        for element in (np.array([[head, item]]), np.array(head), items):
            yield element
        return
    
    for element in combination_step(items[0], items[1:]):
        yield np.append(head, element)
        yield element

    yield head    
        
def perm(A):
    """
    Implementación para obtener las posibles permutacione de una lista
    basado en el algoritmo de heaps. Modificado en base a:
    https://stackoverflow.com/questions/29042819/heaps-algorithm-permutation-generator
    """
    n = len(A)
    Alist = [el for el in A]
    for hp in _heap_perm_(n, Alist):
        yield hp.copy()

def _heap_perm_(n, A):
    """
    Paso recursivo de perm
    """
    if n == 1: yield A
    else:
        for i in range(n-1):
            for hp in _heap_perm_(n-1, A): yield hp
            j = 0 if (n % 2) == 1 else i
            A[j],A[n-1] = A[n-1],A[j]
        for hp in _heap_perm_(n-1, A): yield hp
        
        
def relations(itemset):
    """
    Genera todas las relaciiones antecedente -> consecuente en base a un itemset
    """
    if len(itemset) == 1: # Caso especial
        return []

    perms = list(perm(list(itemset))) # Generamos todas las permutaciones
    ans = set()

    for i in range(1, len(itemset)):
        for permx in perms:
            x = frozenset(permx[: i]) # Con sets eliminamos equivalencias
            y = frozenset(permx[i :])

            ans.add((x, y))

    return list(ans) # Generamos respuesta
        
        
def load_database(path, limit=None):
    """
    Carga una base de datos en formato .npy. Permite limitar el número
    máximo de tuplas leídas.
    """
    data = np.load(path, allow_pickle=True).item()

    if limit is None:
        limit = len(data)

    values = list(data.values())
    data_list = [np.unique(tup) for tup in values[: limit]]
    
    return data_list


class FPTree:
    """
    Posee los metodos fit y generate y otros que permiten
    realizar operaciones para obtener los itemsets
    """
    nodes_by_item = {}
    
    def __init__(self, item_list, support):
        self.item_list = item_list # List of Series
        self.support = support
        self.frequent_items = None # Above the defined support
        self.tree_base = FPNode(None, None)
        self.frequency_itemsets = {}
        self.rules = np.array([])
        self.cached_support = {} # Dict of frozensets
        
        
    def _gen_frequent_items(self):
        """
        Genera los elementos frecuentes que están por sobre el support
        """
        
        base_arr = np.array([])
        base_arr = np.concatenate(self.item_list)
        
        counted = pd.Series(base_arr).value_counts()
        top = counted[counted >= self.support]
        
        self.frequent_items = top.iloc[np.lexsort((top.index, -top.values))]
        
    def fit(self):
        """
        Arma el FP-Tree con la base de datos pasada al construtor
        """
        self._gen_frequent_items()
        np_freq = self.frequent_items.index.to_numpy()
        
        for row in self.item_list:
            ordered = np_freq[np.in1d(np_freq, row, assume_unique=False)]
            
            self.tree_base.process_element(ordered)
        
    
    def conditional_pattern_gen(self, element):
        """
        Genera cada uno de los conditional patternes asociados al elemento
        'element'.
        """        
        for node in FPTree.nodes_by_item[element]:
            path = node.collect_path()
            
            if len(path) > 1:
                yield path 
            
    def conditional_tree(self, element):
        """
        Retorna el conditional FPTree asociado a cada elemento
        """
        conditional_set = False
        conditional = np.array([]) # Performance -> Join all at once?
        for pattern in self.conditional_pattern_gen(element):
            if not conditional_set:
                conditional_set = True
                conditional = pattern
                continue
            
            indexes = np.in1d(conditional, pattern, assume_unique=False)
            conditional = conditional[indexes]
           
        if len(conditional) > 0:
            print("No vacio!", conditional)
            
        return conditional
    
    def frequent_pattern(self, element):
        """
        Genera las combinaciones entre los elementos del conditional FP-Tree
        y el elemento asociado a este.
        """
        
        tree = self.conditional_tree(element)
        for combination in combine(element, tree):
            yield combination
                
    def gen_rules(self):
        """
        Genera todas las reglas de asociacion, sin filtrar por confianza
        """
        all_rules = []
        for element in self.frequent_items.index:
            for combination in self.frequent_pattern(element):
                new_rules = relations(combination)
                if len(new_rules) > 0:
                    all_rules.append(np.array(new_rules))
                
        # print(all_rules)
        self.rules = np.concatenate(all_rules)
        
    def generate(self, confidence):
        """
        Método pedido en el enunciado, recibe una confianza y retorna
        todas las reglas que estan por sobre la confianza
        """
        if len(self.rules) == 0:
            self.gen_rules()
        
        ans = []
        for ant, cons in self.rules:
            conf = self.calculate_confidence(ant, cons)
            if conf >= confidence:
                ans.append((ant, cons, conf))
        
        return ans
                    
    def calculate_support(self, itemset):
        """
        Retorna el soporte de un itemset contado las listas que contienen
        todos los elementos del itemset
        """
        
        itemset = frozenset(itemset)
        
        if itemset in self.cached_support:
            return self.cached_support[itemset]
        
        count = 0
        for row in self.item_list:
            
            contained = True
            for element in itemset:
                if element not in row:
                    contained = False
                    break
                
            if contained:
                count += 1
                
        self.cached_support[itemset] = count
        return count
        
                
    def calculate_confidence(self, antecedent, consecuent):
        """
        Retorna la confianza de una regla dado el antecedente y el consecuente
        """
        both = set(antecedent).union(set(consecuent))
        ant_count = self.calculate_support(antecedent)
        both_count = self.calculate_support(both)
                
        return both_count / ant_count
    
    def calculate_lift(self, antecedent, consecuent):
        """
        Retorna el lift de una regla dado el antecedente y el consecuente
        """
        conf = self.calculate_confidence(antecedent, consecuent)
        sup = self.calculate_support(consecuent)
        
        return conf / sup
        
    
class FPNode:
    """
    Representa cada nodo del FPTree
    """
    
    
    def __init__(self, item, parent, count=1):
        self.item = item
        self.parent = parent
        self.count = count
        self.childs = []
        
    def process_element(self, item_list):class FPNode:
    """
    Representa cada nodo del FPTree
    """
    
    
    def __init__(self, item, parent, count=1):
        self.item = item
        self.parent = parent
        self.count = count
        self.childs = []
        
    def process_element(self, item_list):
        """
        Paso recursivo para generar el árbol
        """
        if len(item_list) == 0:
            return
        
        head = item_list[0]
        
        added = False
        for child in self.childs:
            if child.item == head:
                child.count += 1
                selected_node = child
                added = True
                break
    
        if not added:
            selected_node = self.add_node(head)    
        
        if len(item_list) > 1:
            new_items = item_list[1:]
            selected_node.process_element(new_items)
        
        
    def add_node(self, item):
        """
        Genera un nuevo nodo y lo agrega como hijo 
        """
        
        new_node = FPNode(item, self)
        self.childs.append(new_node)
        
        if item not in FPTree.nodes_by_item:
            FPTree.nodes_by_item[item] = []
            
        FPTree.nodes_by_item[item].append(new_node)
        return new_node
    
    def collect_iter(self):
        """
        Paso recursivo para generar los FP-Tree
        """
        if self.item is None: # Performance -> user yield?
            return np.array([])
        
        return np.append(self.parent.collect_iter(), self.item)
        
    def collect_path(self):
        return self.parent.collect_iter()
    
    def __str__(self):
        return f"{self.item}[{self.count}] -> ({', '.join([str(node) for node in self.childs])})"
    
    
    def __repr__(self):
        return f"{self.item}[{self.count}] -> ({', '.join([f'{node.item}[{node.count}]' for node in self.childs])})"
        """
        Paso recursivo para generar el árbol
        """
        if len(item_list) == 0:
            return
        
        head = item_list[0]
        
        added = False
        for child in self.childs:
            if child.item == head:
                child.count += 1
                selected_node = child
                added = True
                break
    
        if not added:
            selected_node = self.add_node(head)    
        
        if len(item_list) > 1:
            new_items = item_list[1:]
            selected_node.process_element(new_items)
        
        
    def add_node(self, item):
        """
        Genera un nuevo nodo y lo agrega como hijo 
        """
        
        new_node = FPNode(item, self)
        self.childs.append(new_node)
        
        if item not in FPTree.nodes_by_item:
            FPTree.nodes_by_item[item] = []
            
        FPTree.nodes_by_item[item].append(new_node)
        return new_node
    
    def collect_iter(self):
        """
        Paso recursivo para generar los FP-Tree
        """
        if self.item is None: # Performance -> user yield?
            return np.array([])
        
        return np.append(self.parent.collect_iter(), self.item)
        
    def collect_path(self):
        return self.parent.collect_iter()
    
    def __str__(self):
        return f"{self.item}[{self.count}] -> ({', '.join([str(node) for node in self.childs])})"
    
    
    def __repr__(self):
        return f"{self.item}[{self.count}] -> ({', '.join([f'{node.item}[{node.count}]' for node in self.childs])})"
    

if __name__ == "__main__":
    
    from mlxtend.preprocessing import TransactionEncoder
    from mlxtend.frequent_patterns import fpgrowth
    

    # id_counter = 0
    # ids = {}
    # new_base = []
    # base = load_database("spotify.npy", limit=200)
    # for lista in base:
    #     new_row = []
    #     for elemento in lista:
    #         if elemento not in ids:
    #             ids[elemento] = id_counter
    #             id_counter += 1
    #         new_row.append(ids[elemento])
    #     new_base.append(new_row)
        
    # with open("out.txt", "w") as file:
    #     for lista in new_base:
    #         file.write(" ".join([str(x) for x in lista]) + "\n")
     
    
    # dataset = [list(k) for k in base]

    
    # dataset = [list(a) for a in["MONKEY", "DONKEY", "MAKE", "MUCKY", "COKIE"]]
    
    # dataset = new_base[:1000]

    limit = 800
    support = 0.01


    base = load_database("spotify.npy", limit=limit)
    dataset = base

    te = TransactionEncoder()
    te_ary = te.fit(dataset).transform(dataset)  
    df = pd.DataFrame(te_ary, columns=te.columns_)
    new_data = fpgrowth(df, min_support=support, use_colnames=True)
    print(new_data)
    
    
    # test_support = 3
    # test_instance = [['A', 'B', 'D', 'E'], ['B', 'C', 'E'], ['A', 'B', 'D', 'E'], 
    #                 ['A', 'B', 'C', 'E'], ['A', 'B', 'C', 'D', 'E'], ['B', 'C', 'D']]
    
    # test_instance = [list(a) for a in["MONKEY", "DONKEY", "MAKE", "MUCKY", "COKIE"]]

    # series = [pd.Series(row) for row in test_instance]
    
    # tree = FPTree(series, test_support)
    # tree.generate()
    # print(tree.tree_base)
    
    # base = load_database("spotify.npy", limit=200)
    tree = FPTree(base, limit * support)
    
    print("Generando Arbol")
    tree.fit()
    
    itemsets = [list(tree.frequent_pattern(element)) for element in tree.frequent_items.index]
    
    print("Itemsets mios:", len(itemsets))
    print("Itemsets ellos:", len(new_data))
    
    print("Generanodo las reglas")
    tree.gen_rules()
    
    # print(list(tree.frequent_pattern("Closer")))
    
    
# (B -> (E -> (D -> (A -> ()), C -> (A -> (), D -> (A -> ()))), C -> (D -> ())))

# None[1] -> (B[6] -> (E[5] -> (D[3] -> (A[2] -> (), C[1] -> (A[1] -> ())), C[2] -> (A[1] -> ())), D[1] -> (C[1] -> ())))